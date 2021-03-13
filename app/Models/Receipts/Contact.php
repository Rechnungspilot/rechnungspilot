<?php

namespace App\Models\Receipts;

use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Contact extends Pivot
{
    protected $appends = [
        'created_at_formatted',
    ];

    protected $with = [
        'user',
    ];

    public function getCreatedAtFormattedAttribute() : string
    {
        return $this->created_at->format('d.m.Y H:i');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
