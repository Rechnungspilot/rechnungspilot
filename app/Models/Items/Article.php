<?php

namespace App\Models\Items;

use App\Traits\HasCompany;
use Carbon\Carbon;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

class Article extends Model
{
    use HasCompany,
        HasFactory,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'items.articles';

    protected $appends = [
        'unit_price_formatted',
        'unit_value_formatted',
        'created_at_date_formatted',
    ];

    protected $fillable = [
        'company_id',
        'item_id',
        'unit_cost',
        'unit_price',
        'unit_value',
        'unit_value_formatted',
        'unit_price_formatted',
    ];

    protected $table = 'item_articles';

    public static function labels() : array
    {
        return [
            'nominativ' => [
                'singular' => 'Artikel',
                'plural' => 'Artikel',
            ],
        ];
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function getCreatedAtDateFormattedAttribute(): string
    {
        return $this->created_at->format('d.m.Y');
    }

    public function getUnitPriceFormattedAttribute(): string
    {
        return number_format($this->attributes['unit_price'], 2, ',', '');
    }

    public function getUnitValueFormattedAttribute(): string
    {
        return number_format($this->attributes['unit_value'], 2, ',', '');
    }

    public function setUnitValueFormattedAttribute($value): void
    {
        $this->attributes['unit_value'] = str_replace(',', '.', $value);
        Arr::forget($this->attributes, 'unit_value_formatted');
    }

    public function setUnitPriceFormattedAttribute($value): void
    {
        $this->attributes['unit_price'] = str_replace(',', '.', $value);
        Arr::forget($this->attributes, 'unit_price_formatted');
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'article' => $this->id,
            'item' => $this->item_id,
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(\App\Item::class, 'item_id');
    }

    public function receipt_item(): HasMany
    {
        return $this->hasMany(\App\Receipts\Item::class, 'item_article_id');
    }

    public function scopeIsAvailable(Builder $query, $value): Builder
    {
        if (is_null($value)) {
            return $query;
        }

        if (! $value) {
            return $query->whereHas('receipt_item', function ($query) {
                return $query->whereHas('receipt', function ($query) {
                    return $query->where('type', \App\Receipts\Invoice::class);
                });
            });
        }

        return $query->whereDoesntHave('receipt_item', function ($query) {
            return $query->whereHas('receipt', function ($query) {
                return $query->where('type', \App\Receipts\Invoice::class);
            });
        });
    }

    public function scopeCreatedAtDate(Builder $query, $value): Builder
    {
        if (is_null($value)) {
            return $query;
        }

        return $query->whereDate('created_at', new Carbon($value));
    }
}
