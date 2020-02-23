<?php

namespace App\Receipts\Statuses;

use App\Receipts\Statuses\Status;
use Parental\HasParent;
use Illuminate\Database\Eloquent\Model;

class Expensed extends Status
{
    use HasParent;

    const NAME = 'Gebucht';
    const RANK = 7;

    protected $action = 'buchen';

    public function getNameAttribute()
    {
        return self::NAME;
    }
}
