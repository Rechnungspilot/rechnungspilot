<?php

namespace App\Traits;

use App\Scopes\HasCompanyScope;

trait HasCompany
{
    public static function bootHasCompany()
    {
        static::addGlobalScope(new HasCompanyScope);
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }
}