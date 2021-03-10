<?php

namespace App\Banks;

use App\Banks\Bank;
use App\Traits\HasCompany;
use App\Transaction;
use Carbon\Carbon;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Fhp\Model\SEPAAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Account extends Model
{
    use HasCompany,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'banks.accounts';
    const TYPE = 'accounts';

    protected $sepaAccount;

    protected $uri = '/konten';

    protected $appends = [
        'path',
    ];

    protected $fillable = [
        'amount',
        'bank_company_id',
        'company_id',
        'default',
        'iban',
        'name',
    ];

    protected static function labels() : array
    {
        return [
            'nominativ' => [
                'singular' => 'Konto',
                'plural' => 'Konten',
            ],
        ];
    }

    public static function fromIban(string $iban)
    {
        return self::withoutGlobalScopes()->with('bank.bank')->where('iban', $iban)->get()->first();
    }

    public function bank()
    {
        return $this->belongsTo('App\Banks\Company', 'bank_company_id');
    }

    public function banks()
    {
        return $this->belongsToMany('App\Banks\Bank', 'bank_company', 'id', 'bank_id')
            ->using('App\Banks\Company')
            ->withPivot('id', 'username', 'pin')
            ->withTimestamps();
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'account' => $this->id,
        ];
    }

    public function getSyncPathAttribute()
    {
        return route('banks.accounts.sync', [
            'account' => $this->id,
        ]);
    }

    public function import(Carbon $from, Carbon $to, bool $guessCompany = false)
    {
        $transactions = [];
        $statements = $this->getStatements($from, $to);
        foreach ($statements->getStatements() as $statement) {
            $amount_transactions = 0;
            foreach ($statement->getTransactions() as $SEPATransaction) {
                $transaction = Transaction::firstOrNew([
                    'account_id' => $this->id,
                    'amount' => $SEPATransaction->getAmount() * 100,
                    'company_id' => $this->company_id,
                    'date' => $SEPATransaction->getValutaDate(),
                    'reference' => Arr::get(explode("SVWZ+", $SEPATransaction->getDescription1()), 1, ''),
                    'text' => $SEPATransaction->getDescription1() . "\n" . $SEPATransaction->getDescription2(),
                    'type' => $SEPATransaction->getCreditDebit(),
                    'name' => $SEPATransaction->getName(),
                    'iban' => $SEPATransaction->getAccountnumber(),
                ]);

                if ($guessCompany == true) {
                    $transaction->guessCompany();
                }

                $transaction->save();

                $transactions[] = $transaction;

                $amount_transactions += ($SEPATransaction->getAmount() * ($SEPATransaction->getCreditDebit() == \Fhp\Model\StatementOfAccount\Transaction::CD_DEBIT ? -1 : 1));
            }

            $amount_start_balance = $statement->getStartBalance() * ($statement->getCreditDebit() == \Fhp\Model\StatementOfAccount\Statement::CD_DEBIT ? -1 : 1);
            $amount = ($amount_start_balance + $amount_transactions) * 100;
        }

        $this->update([
            'amount' => $amount,
        ]);

        $this->bank->update([
            'last_import_at' => now(),
        ]);

        return $transactions;
    }

    public function getStatements(Carbon $from, Carbon $to)
    {
        return $this->bank->getStatementOfAccount($this->getSepaAccount(), $from, $to);
    }

    protected function getSepaAccount() : SEPAAccount
    {
        if (isset($this->sepaAccount)) {
            return $this->sepaAccount;
        }

        $sepaAccounts = $this->bank->getSepaAccounts();
        foreach($sepaAccounts as $sepaAccount) {
            if ($sepaAccount->getIban() == $this->iban) {
                $this->sepaAccount = $sepaAccount;
                break;
            }
        }

        if (! isset($this->sepaAccount)) {
            throw new Exception('Account nicht gefunden');
        }

        return $this->sepaAccount;
    }

    public static function setup(int $companyId)
    {
        self::create([
            'company_id' => $companyId,
            'bank_company_id' => 0,
            'name' => 'Bar',
            'iban' => '',
        ]);

        self::create([
            'company_id' => $companyId,
            'bank_company_id' => 0,
            'name' => 'Kasse',
            'iban' => '',
        ]);
    }
}
