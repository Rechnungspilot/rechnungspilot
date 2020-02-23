<?php

namespace App\Receipts\Statuses;

use App\Receipts\Statuses\Status;
use Illuminate\Database\Eloquent\Model;
use Parental\HasParent;

class Overdue extends Status
{
    use HasParent;

    const NAME = 'Überfällig';
    const RANK = 4;

    public function getNameAttribute()
    {
        return self::NAME;
    }
}
