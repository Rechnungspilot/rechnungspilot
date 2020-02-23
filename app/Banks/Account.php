<?php

namespace App\Banks;

use App\Banks\Bank;
use App\Traits\HasCompany;
use App\Transaction;
use Carbon\Carbon;
use Fhp\Model\SEPAAccount;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasCompany;

    protected $sepaAccount;
    protected $bank;

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

    public function getPathAttribute()
    {
        return $this->uri . '/' . $this->id;
    }

    public function import(Carbon $from, Carbon $to, bool $guessCompany = false)
    {
        $transactions = [];
        $statements = $this->getStatements($from, $to);
        foreach ($statements->getStatements() as $statement)
        {
            $date = new Carbon($statement->getDate()->format('Y-m-d'));
            foreach ($statement->getTransactions() as $SEPATransaction)
            {
                $transaction = Transaction::make([
                    'account_id' => $this->id,
                    'amount' => $SEPATransaction->getAmount() * 100,
                    'company_id' => $this->company_id,
                    'date' => $date,
                    'text' => $SEPATransaction->getDescription1() . "\n" . $SEPATransaction->getDescription2(),
                    'type' => $SEPATransaction->getCreditDebit(),
                    'name' => $SEPATransaction->getName(),
                    'iban' => $SEPATransaction->getAccountnumber(),
                ]);

                if ($guessCompany == true)
                {
                    $transaction->guessCompany();
                }

                $transaction->save();

                $transactions[] = $transaction;
            }
        }

        return $transactions;
    }

    public function getStatements(Carbon $from, Carbon $to)
    {
        return $this->getBank()->getStatementOfAccount($this->getSepaAccount(), $from, $to);
    }

    protected function getBank()
    {
        // $bankCompany = $this->getRelation('bank');
        // $bank = $bankCompany->getRelation('bank');
        // $bank->pivot = $bankCompany;
        return $this->getRelation('bank');
    }

    protected function getSepaAccount() : SEPAAccount
    {
        if (isset($this->sepaAccount))
        {
            return $this->sepaAccount;
        }

        $sepaAccounts = $this->getBank()->getSepaAccounts();
        foreach($sepaAccounts as $sepaAccount) {
            if ($sepaAccount->getIban() == $this->iban)
            {
                $this->sepaAccount = $sepaAccount;
                break;
            }
        }

        if (! isset($this->sepaAccount))
        {
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
