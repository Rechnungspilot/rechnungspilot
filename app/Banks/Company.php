<?php

namespace App\Banks;

use App\Traits\HasCompany;
use Carbon\Carbon;
use Fhp\FinTs;
use Fhp\Model\SEPAAccount;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Crypt;

class Company extends Pivot
{
    use HasCompany;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;
    protected $fints;
    public $table = 'bank_company';

    protected $fillable = [
        'bank_id',
        'company_id',
        'username',
        'pin',
    ];

    public function bank()
    {
        return $this->belongsTo('App\Banks\Bank');
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

    public function accounts()
    {
        return $this->hasMany('App\Banks\Accounts');
    }

    public function getSepaAccounts()
    {
        return $this->getFints()->getSEPAAccounts();
    }

    public function getStatementOfAccount(SEPAAccount $account, Carbon $from, Carbon $to)
    {
        return $this->getFints()->getStatementOfAccount($account, $from, $to);
    }

    protected function getFints()
    {
        if (!is_null($this->fints))
        {
            return $this->fints;
        }

        $this->fints = new FinTs(
            $this->bank->url,
            443,
            $this->bank->blz,
            $this->username,
            $this->pin,
            null,
            config('app.fhp_registration_no'),
            '1.0'
        );

        $variables = $this->fints->getVariables();
        $this->fints->setTANMechanism(array_keys($variables->tanModes)[0]);

        return $this->fints;
    }
}
