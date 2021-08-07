<?php

namespace App\Receipts\Statuses;

use App\Banks\Account;
use App\Receipts\Expense;
use App\Receipts\Receipt;
use App\Receipts\Statuses\Status;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Parental\HasParent;

class Payment extends Status
{
    use HasParent;

    const NAME = 'Zahlung';
    const RANK = 5;

    protected $action = 'Zahlung erfassen';
    protected $credit;

    public function getNameAttribute()
    {
        return self::NAME;
    }

    public function getAmountAttribute()
    {
        return $this->data['amount'];
    }

    public function getTippAttribute()
    {
        return Arr::get($this->data, 'tipp', 0);
    }

    public function getDescriptionAttribute()
    {
        return number_format($this->amount / 100, 2, ',', '.') . ' € wurden ' . ($this->credit() ? ' mit <a href="' . $this->credit->path . '">' . $this->credit->typeName . ' ' . $this->credit->name . '</a> verrechnet' : ' bezahlt') . '.' . ($this->tipp ? '<br /><span class="text-muted">Trinkgeld: ' . number_format($this->tipp / 100, 2, ',', '.') . ' €</span>' : '');
    }

    public function getDataAttributesAttribute()
    {
        $accounts = Account::all();

        return [
            'amount' => [
                'label' => 'Betrag',
                'value' => number_format(($this->receipt->outstandingBalance / 100), 2, ',', ''),
                'small' => $this->receipt->latest_dun_id ? $this->receipt->latestDun->settings->level->name . ' vom ' . $this->receipt->latestDun->date->format('d.m.Y') . ' über ' . number_format($this->receipt->latestDun->gross / 100, 2, ',', '.') . ' €' : '',
            ],
            'completed' => [
                'label' => 'Bei Teilzahlung: Rechnung als erledigt markieren?',
                'type' => 'checkbox',
                'value' => 1,
                'checked' => false,
            ],
            'account_id' => [
                'type' => 'select',
                'label' => 'Konto',
                'options' => $accounts,
                'value' => $accounts->first()->id ?? 0,
            ],
        ];
    }

    protected function associated()
    {
        if ($this->receipt->isPayed() || $this->data['completed'])
        {
            $this->receipt->paid($this->date);
        }
    }

    protected function handleAttributes(array $attributes) : array
    {
        $attributes['amount'] = str_replace(',', '.', $attributes['amount']) * 100;
        $attributes['completed'] = $attributes['completed'] ?? false;
        if (array_key_exists('transaction_id', $attributes) && $attributes['transaction_id'] > 0)
        {
            return $attributes;
        }

        $transaction = Transaction::create([
            'account_id' => $attributes['account_id'],
            'company_id' => $this->company_id,
            'amount' => $attributes['amount'],
            'type' => get_class($this->receipt) == Expense::class ? 'debit' : 'credit',
            'date' => $this->date,
            'reference' => '',
        ]);
        $attributes['transaction_id'] = $transaction->id;

        return $attributes;
    }

    protected function credit()
    {
        if (! is_null($this->credit))
        {
            return $this->credit;
        }

        if (! array_key_exists('receipt_id', $this->data))
        {
            return null;
        }

        $this->credit = Receipt::findOrFail($this->data['receipt_id']);

        return $this->credit;
    }
}
