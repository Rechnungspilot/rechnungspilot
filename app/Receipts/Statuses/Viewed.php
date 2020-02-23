<?php

namespace App\Receipts\Statuses;

use Illuminate\Database\Eloquent\Model;
use Parental\HasParent;

class Viewed extends Model
{
    use HasParent;

    const NAME = 'Gesehen';
    const RANK = 3;

    public function getNameAttribute()
    {
        return self::NAME;
    }
}
