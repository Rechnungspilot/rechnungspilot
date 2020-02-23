<?php

namespace App\Receipts\Statuses;

use App\Receipts\Statuses\Status;
use Parental\HasParent;
use Illuminate\Database\Eloquent\Model;

class Completed extends Status
{
    use HasParent;

    const NAME = 'Abgeschlossen';
    const RANK = 6;

    protected $action = 'abschließen';

    public function getNameAttribute()
    {
        return self::NAME;
    }
}
