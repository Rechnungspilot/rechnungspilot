<?php

namespace App\Receipts;

use App\Jobs\CacheItem;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasCompany;

    protected $appends = [
        'unitPriceFormated',
    ];

    protected $casts = [
        'unit_price' => 'float',
        'quantity' => 'float',
        'discount' => 'float',
    ];

    protected $fillable = [
        'company_id',
        'description',
        'discount',
        'gross',
        'item_id',
        'name',
        'net',
        'quantity',
        'receipt_id',
        'tax',
        'unit_id',
        'unit_price',
    ];

    protected $table = 'item_receipt';

    /**
     * The booting method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            if (! $model->company_id)
            {
                $model->company_id = auth()->user()->company_id;
            }

            return true;
        });
    }

    public static function cacheAll(Collection $items)
    {
        $unique = $items->unique('item_id');
        foreach ($unique as $receiptItem)
        {
            if ($receiptItem->item_id == 0)
            {
                continue;
            }
            CacheItem::dispatch($receiptItem->item);
        }
    }

    public function getUnitPriceFormatedAttribute()
    {
        $min = 2;
        $max = 6;
        $price = $this->attributes['unit_price'] == 0 ? 0 : rtrim($this->attributes['unit_price'], 0);
        $decimals = strlen(substr( $price, (strpos($price, '.')+1) ) );
        $decimals = (int)($decimals < $min ? $min : ($decimals > $max ? $max : $decimals));

        return number_format($price, $decimals, ',', '');
    }

    /**
     * Gibt die notwendige Anzahl Nachkommastellen zurück, die benötigt wird
     */
    protected function decimals(float $zahl=0, int $min=0, int $max=6): int
    {
        $zahl = rtrim($zahl, 0);
        $nachkommastellen = strlen(substr( $zahl, (strpos($zahl, '.')+1) ) );

        return (int)($nachkommastellen < $min ? $min : ($nachkommastellen > $max ? $max : $nachkommastellen));
    }

    public function setQuantityAttribute($value) {
        $this->attributes['quantity'] = str_replace(',', '.', $value);
    }

    public function setDiscountAttribute($value)
    {
        $this->attributes['discount'] = str_replace(',', '.', $value) / 100;
    }

    public function setUnitPriceAttribute($value)
    {
        $this->attributes['unit_price'] = str_replace(',', '.', $value);
        $this->attributes['net'] = $this->attributes['unit_price'] * $this->attributes['quantity'] * (1 - $this->attributes['discount']) * 100;
        $this->attributes['gross'] = $this->attributes['net'] * (1 + $this->attributes['tax']);
    }

    public function item()
    {
        return $this->belongsTo('App\Item');
    }

    public function receipt()
    {
        return $this->belongsTo('App\Receipts\Receipt');
    }

    public function receiptable()
    {
        return $this->morphTo('receiptable');
    }

    public function morphedItems()
    {
        return $this->morphMany(self::class, 'receiptable')->orderBy('created_at', 'DESC');
    }

    public function unit()
    {
        return $this->belongsTo('App\Unit');
    }

    public function isDeletable() : bool
    {
        return true;
    }
}
