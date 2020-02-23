<?php

namespace App\Receipts\Statuses;

use App\Receipts\Statuses\Status;
use Parental\HasParent;
use Illuminate\Database\Eloquent\Model;

class Payed extends Status
{
    use HasParent;

    const NAME = 'Bezahlt';
    const RANK = 6;

    public function getNameAttribute()
    {
        if ((array_key_exists('outstanding', $this->data) && $this->data['outstanding'] > 0))
        {
            return 'Erledigt';
        }

        return self::NAME;
    }

    public function getDescriptionAttribute()
    {
        return ((array_key_exists('outstanding', $this->data) && $this->data['outstanding'] > 0) ? 'Offener Betrag: ' . number_format($this->data['outstanding'] / 100, 2, ',', '.') . ' €' : '');
    }
}
