<?php

namespace App\Receipts\Statuses;

use App\Receipts\Statuses\Status;
use Parental\HasParent;
use Illuminate\Database\Eloquent\Model;

class Accepted extends Status
{
    use HasParent;

    const NAME = 'Angenommen';
    const RANK = 4;

    protected $action = 'annehmen';

    public function getNameAttribute()
    {
        return self::NAME;
    }
}
