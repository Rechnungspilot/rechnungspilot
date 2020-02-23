<?php

namespace App\Receipts\Statuses;

use Illuminate\Database\Eloquent\Model;
use Parental\HasParent;

class Canceled extends Model
{
    use HasParent;

    const NAME = 'Stroniert';
    const RANK = 5;

    protected $action = 'stornieren';

    public function getNameAttribute()
    {
        return self::NAME;
    }
}
