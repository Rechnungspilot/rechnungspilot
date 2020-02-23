<?php

namespace App\Receipts\Statuses;

use App\Receipts\Receipt;
use App\Receipts\Statuses\Status;
use Illuminate\Database\Eloquent\Model;
use Parental\HasParent;

class MorphedTo extends Status
{
    use HasParent;

    const NAME = 'Beleg erstellt';
    const RANK = 5;

    protected $action = 'Beleg erstellen';

    public function getNameAttribute()
    {
        if ($receipt = $this->toReceipt())
        {
            return $receipt->typeName . ' erstellt';
        }

        return self::NAME;
    }

    public function getDescriptionAttribute()
    {
        if ($receipt = $this->toReceipt())
        {
            return '<a href="' . $receipt->path . '">' . $receipt->typeName . ' ' . $receipt->name . '</a> erstellt';
        }

        return '';
    }

    public function setActionBelegTyp(string $belegtyp = 'Rechnung') {
        $this->action = $belegtyp . ' erstellen';
    }

    protected function toReceipt()
    {
        if (! array_key_exists('to_id', $this->data) || ! $this->data['to_id'])
        {
            return null;
        }

        return Receipt::find($this->data['to_id']);
    }
}
