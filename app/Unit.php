<?php

namespace App;

use App\Item;
use App\Traits\HasCompany;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasCompany,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'items.units';

    protected $appends = [
        'create_path',
        'edit_path',
        'index_path',
        'path',
    ];

    protected $fillable = [
        'abbreviation',
        'company_id',
        'name',
    ];

    public static function labels() : array
    {
        return [
            'nominativ' => [
                'singular' => 'Einheit',
                'plural' => 'Einheiten',
            ],
        ];
    }

    public function items() : HasMany
    {
        return $this->hasMany(Item::class, 'unit_id');
    }

    public function isDeleteable() : bool
    {
        return (! $this->items()->exists());
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'unit' => $this->id,
        ];
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

        $unit = self::create([
            'abbreviation' => 'h',
            'company_id' => $companyId,
            'name' => 'Stunden',
        ]);
    }
}
