<?php

namespace App\Items;

use App\Traits\HasCompany;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasCompany;

    protected $casts = [
        'start_at' => 'date',
    ];

    protected $fillable = [
        'company_id',
        'start_at',
        'unit_price',
        'unit_cost',
    ];

    protected $table = 'item_price';

    public static function validAt(int $itemId, Carbon $date) : self
    {
        return self::where('item_id', $itemId)
            ->whereDate('start_at', '<=', $date)
            ->latest()
            ->first();
    }

    public function item()
    {
        return $this->belongsTo('App\Item');
    }
}
