<?php

namespace App\Receipts\Duns;

use App\Item;
use App\Scopes\HasCompanyScope;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasCompany;

    protected $fillable = [
        'action',
        'amount',
        'attach_invoice',
        'company_id',
        'description',
        'item_id',
        'level',
        'name',
        'waiting_days',
    ];

    protected $table = 'dun_levels';
    protected $uri = '/einstellungen/mahnstufen';

    public static function nextNumber()
    {
        return self::max('level') + 1;
    }

    public static function nextLevel(int $level = 0)
    {
        if ($level == 0)
        {
            return self::with(['item'])->orderBy('level', 'ASC')->first();
        }

        return self::with(['item'])->where('level', '>', $level)->orderBy('level', 'ASC')->first();
    }


    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = str_replace(',', '.', $value) * 100;
    }

    public function getPathAttribute()
    {
        return $this->uri . '/' . $this->id;
    }

    public function item()
    {
        return $this->belongsTo('App\Item');
    }

    public static function setup(int $companyId)
    {
        $item = Item::withoutGlobalScope(HasCompanyScope::class)->where('company_id', $companyId)
            ->where('name', 'Mahngebühr')
            ->first();

        $levels = [1, 2, 3, 4];
        $levels_count = count($levels);
        foreach ($levels as $key => $level) {
            $name = (($level - 1) == 0 ? 'Zahlungserinnerung' : ($level == $levels_count ? 'Letzte Mahnung' : $level - 1 . '. Mahnung'));
            self::create([
                'level' => $level,
                'company_id' => $companyId,
                'name' => $name,
                'item_id' => ($level > 1 ? $item->id : 0),
                'amount' => ($level - 1) * 5,
            ]);
        }
    }
}
