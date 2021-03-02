<?php

namespace App\Banks;

use App\Exceptions\Banks\NeedsTanException;
use App\Traits\HasCompany;
use Carbon\Carbon;
use Fhp\FinTs;
use Fhp\Model\SEPAAccount;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class Company extends Pivot
{
    use HasCompany;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    public $table = 'bank_company';

    protected $options;
    protected $fints;
    protected $credentials;

    protected $appends = [
        //
    ];

    protected $dates = [
        'last_import_at',
    ];

    protected $fillable = [
        'bank_id',
        'company_id',
        'username',
        'pin',
        'last_import_at',
    ];

    public function bank()
    {
        return $this->belongsTo('App\Banks\Bank', 'bank_id');
    }

    public function getUsernameAttribute()
    {
        return Crypt::decryptString($this->attributes['username']);
    }

    public function getPinAttribute()
    {
        return Crypt::decryptString($this->attributes['pin']);
    }

    public function setUsernameAttribute($value)
    {
        $this->attributes['username'] = Crypt::encryptString($value);
    }

    public function setPinAttribute($value)
    {
        $this->attributes['pin'] = Crypt::encryptString($value);
    }

    public function getActionAttribute()
    {
        return $this->action;
    }

    public function accounts()
    {
        return $this->hasMany('App\Banks\Accounts');
    }


    public function getSepaAccounts()
    {
        $getSepaAccounts = \Fhp\Action\GetSEPAAccounts::create();
        $this->getFints()->execute($getSepaAccounts);

        return $getSepaAccounts->getAccounts();
    }

    public function getStatementOfAccount(SEPAAccount $account, Carbon $from, Carbon $to)
    {
        $getStatement = \Fhp\Action\GetStatementOfAccount::create($account, $from, $to);
        $this->getFints()->execute($getStatement);

        return $getStatement->getStatement();
    }

    public function getTanModes() : array
    {
        $tanModes = $this->fints->getTanModes();
        if (empty($tanModes)) {
            return [];
        }

        return $tanModes;
    }

    public function getTanModeNames(array $tanModes) : array
    {
        $tanModeNames = array_map(function (\Fhp\Model\TanMode $tanMode) {
            return $tanMode->getName();
        }, $tanModes);

        return $tanModeNames;
    }

    protected function setFints($persisted_instance = null) {
        $this->options = new \Fhp\Options\FinTsOptions();
        $this->options->url = $this->bank->url; // HBCI / FinTS Url can be found here: https://www.hbci-zka.de/institute/institut_auswahl.htm (use the PIN/TAN URL)
        $this->options->bankCode = $this->bank->blz; // Your bank code / Bankleitzahl
        $this->options->productName = config('app.fhp_registration_no'); // The number you receive after registration / FinTS-Registrierungsnummer
        $this->options->productVersion = '1.0'; // Your own Software product version
        $this->credentials = \Fhp\Options\Credentials::create($this->username, $this->pin); // This is NOT the PIN of your bank card!
        $this->fints = \Fhp\FinTs::new($this->options, $this->credentials, $persisted_instance);

        // Tanmode und tanmedium in DB speichern
        $tanModes = $this->getTanModes();
        if (count($tanModes)) {
            $tanModeIndex = array_keys($tanModes)[0];
            $tanMode = $tanModes[$tanModeIndex];
            if ($tanMode->needsTanMedium()) {
                $tanMedia = $this->fints->getTanMedia($tanMode);
                if (empty($tanMedia)) {
                    echo 'Your bank did not provide any TAN media, even though it requires selecting one!';
                    return;
                }

                $tanMediaNames = array_map(function (\Fhp\Model\TanMedium $tanMedium) {
                    return $tanMedium->getName();
                }, $tanMedia);
                $tanMediumIndex = array_keys($tanMediaNames)[0];
                $tanMedium = $tanMedia[$tanMediumIndex];
            }
            else {
                $tanMedium = null;
            }
            $this->fints->selectTanMode($tanMode, $tanMedium);
        }

        return $this->fints;
    }

    protected function login() {
        $login = $this->fints->login();
        $this->handleNeedsTan($login);
    }

    protected function handleNeedsTan(\Fhp\BaseAction $action) {
        if (! $action->needsTan()) {
            return;
        }

        $path = $this->id . '-' . now()->format('Y-m-d_H:i:s') . '-persistedaction.txt';
        Storage::put($path, serialize([$this->fints->persist(), $action]));

        $this->action = $action;
        $this->fints = null;

        $e = new NeedsTanException('Aktion braucht eine TAN', $action, $path);
        throw $e;
    }

    protected function getFints()
    {
        if (!is_null($this->fints)) {
            return $this->fints;
        }

        $this->setFints();

        $this->login();

        return $this->fints;
    }

    public function requestTan(\Fhp\BaseAction $action) : string
    {
        $html = '';

        // Find out what sort of TAN we need, tell the user about it.
        $tanRequest = $action->getTanRequest();
        $html .= 'The bank requested a TAN, asking: ' . $tanRequest->getChallenge() . "\n";
        if ($tanRequest->getTanMediumName() !== null) {
            $html .= 'Please use this device: ' . $tanRequest->getTanMediumName() . "\n";
        }

        // Challenge Image for PhotoTan/ChipTan
        if ($tanRequest->getChallengeHhdUc()) {
            $challengeImage = new \Fhp\Model\TanRequestChallengeImage(
                $tanRequest->getChallengeHhdUc()
            );
            $html .= 'There is a challenge image.' . PHP_EOL;
            // Save the challenge image somewhere
            // Alternative: HTML sample code
            $html .= '<img src="data:' . htmlspecialchars($challengeImage->getMimeType()) . ';base64,' . base64_encode($challengeImage->getData()) . '" />' . PHP_EOL;
        }

        // Optional: Instead of printing the above to the console, you can relay the information (challenge and TAN medium)
        // to the user in any other way (through your REST API, a push notification, ...). If waiting for the TAN requires
        // you to interrupt this PHP execution and the TAN will arrive in a fresh (HTTP/REST/...) request, you can do so:
        // $persistedAction = serialize($action);
        // $persistedFints = $this->getFints()->persist();

        // These are two strings (watch out, they are NOT necessarily UTF-8 encoded), which you can store anywhere.
        // This example code stores them in a text file, but you might write them to your database (use a BLOB, not a
        // CHAR/TEXT field to allow for arbitrary encoding) or in some other storage (possibly base64-encoded to make it
        // ASCII).

        // Storage::put('state.txt', serialize([$persistedFints, $persistedAction]));

        return $html;
    }

    public function submitTan(string $tan, string $path)
    {
        $restored_state = Storage::get($path);
        list($persisted_instance, $persisted_action) = unserialize($restored_state);

        $this->setFints($persisted_instance);

        $response = $this->fints->submitTan($persisted_action, $tan);

        // TODO, wenn erfolgreich Datei löschen

        return $response;
    }
}
