<?php

namespace App\Receipts\Statuses;

use App\Receipts\Statuses\Status;
use Illuminate\Database\Eloquent\Model;
use Parental\HasParent;

class Expired extends Status
{
    use HasParent;

    const NAME = 'Abgelaufen';
    const RANK = 3;

    public function getNameAttribute()
    {
        return self::NAME;
    }
}
