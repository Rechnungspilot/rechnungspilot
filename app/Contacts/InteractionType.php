<?php

namespace App\Contacts;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InteractionType extends Model
{
    use HasCompany;

    protected $fillable = [
        'company_id',
        'name',
    ];

    public static function setup(int $companyId)
    {
        $names = [
            'E-Mail',
            'Persönliches Gespräch',
            'Telefonat',
        ];
        foreach ($names as $key => $name) {
            factory(self::class)->create([
                'name' => $name,
                'company_id' => $companyId,
            ]);
        }
    }
}
