<?php

namespace App;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasCompany;

    protected $fillable = [
        'abbreviation',
        'company_id',
        'name',
    ];

    public function items()
    {
        return $this->hasMany('App\Item');
    }

    public function isDeleteable()
    {
        return ! $this->items()->exists();
    }

    public function scopeSearch($query, $searchtext)
    {
        if ($searchtext == '') {
            return $query;
        }

        return $query->where( function ($query) use($searchtext)
        {
            $query
                ->orWhere('name', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('abbreviation', 'LIKE', '%' . $searchtext . '%');
        });
    }

    public static function setup(int $companyId)
    {
        self::create([
            'abbreviation' => 'stk',
            'company_id' => $companyId,
            'name' => 'Stück',
        ]);

        self::create([
            'abbreviation' => '€',
            'company_id' => $companyId,
            'name' => 'Euro',
        ]);

        self::create([
            'abbreviation' => 'kg',
            'company_id' => $companyId,
            'name' => 'Kilogramm',
        ]);

        self::create([
            'abbreviation' => 'h',
            'company_id' => $companyId,
            'name' => 'Stunden',
        ]);
    }
}
