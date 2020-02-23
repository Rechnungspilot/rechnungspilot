<?php

namespace App\Receipts\Statuses;

use Illuminate\Database\Eloquent\Model;
use Parental\HasParent;

class Received extends Status
{
    use HasParent;

    const NAME = 'Empfangen';
    const RANK = 2;

    protected $action = 'empfangen';

    public function getNameAttribute()
    {
        return self::NAME;
    }
}
