<?php

namespace App\Receipts\Statuses;

use App\Receipts\Statuses\Status;
use Illuminate\Database\Eloquent\Model;
use Parental\HasParent;

class InProgress extends Status
{
    use HasParent;

    const NAME = 'In Bearbeitung';
    const RANK = 3;

    protected $action = 'bearbeiten';

    public function getNameAttribute()
    {
        return self::NAME;
    }
}
