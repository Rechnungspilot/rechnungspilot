<?php

namespace App;

use App\Company;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Transaction extends Model
{
    use HasTags, HasJsonRelationships;

    protected $appends = [

    ];

    protected $casts = [
        'date' => 'date',
    ];

    protected $fillable = [
        'account_id',
        'amount',
        'company_id',
        'date',
        'text',
        'transactionable_id',
        'transactionable_type',
        'type',
        'name',
        'iban',
    ];

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = str_replace(',', '.', $value);
    }

    public function transactionable()
    {
        return $this->morphTo('transactionable')
            ->with('receipt.contact');
    }

    public function payments()
    {
        return $this->hasMany('App\Receipts\Statuses\Status', 'data->transaction_id');
    }

    public function guessCompany()
    {
        $re = '/Rechnungspilot \d+/m';
        preg_match($re, $this->text, $matches);
        if (! empty($matches))
        {
            list($text, $companyId) = explode(' ', $matches[0]);
            $company = Company::find((int) $companyId);
            if ($company)
            {
                $this->transactionable_type = Company::class;
                $this->transactionable_id = $company->id;
                $amount = $this->amount * ($this->type == 'credit' ? 1 : -1);
                $company->increment('balance', $amount);
            }
        }
    }

    public function guessReceipt()
    {

    }

    public function scopeAccount(Builder $query, int $accountId) : Builder
    {
        if ($accountId == 0)
        {
            return $query;
        }

        return $query->where('account_id', $accountId);
    }

    public function scopeCompany(Builder $query, int $companyId) : Builder
    {
        if ($companyId == 0)
        {
            return $query;
        }

        return $query->where('transactionable_type', Company::class)
            ->where('transactionable_id', $companyId);
    }
}
