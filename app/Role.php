<?php

namespace App;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Guard;

class Role extends \Spatie\Permission\Models\Role
{
    use HasCompany;

    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        if (static::where('name', $attributes['name'])->where('company_id', $attributes['company_id'])->where('guard_name', $attributes['guard_name'])->first()) {
            throw RoleAlreadyExists::create($attributes['name'], $attributes['guard_name']);
        }

        if (isNotLumen() && app()::VERSION < '5.4') {
            return parent::create($attributes);
        }

        return static::query()->create($attributes);
    }
}
