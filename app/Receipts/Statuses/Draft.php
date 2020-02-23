<?php

namespace App\Receipts\Statuses;

use App\Receipts\Receipt;
use App\Receipts\Statuses\Status;
use Illuminate\Database\Eloquent\Model;
use Parental\HasParent;

class Draft extends Status
{
    use HasParent;

    const NAME = 'Entwurf';
    const RANK = 1;

    public function getNameAttribute()
    {
        return self::NAME;
    }

    public function getDescriptionAttribute()
    {
        if ($receipt = $this->fromReceipt())
        {
            return 'Erstellt aus <a href="' . $receipt->path . '">' . $receipt->typeName . ' ' . $receipt->name . '</a>';
        }

        return '';
    }

    protected function fromReceipt()
    {
        if (! array_key_exists('from_id', $this->data) || ! $this->data['from_id'])
        {
            return null;
        }

        return Receipt::find($this->data['from_id']);
    }
}
