<?php

namespace App\Banks;

use Carbon\Carbon;
use Fhp\FinTs;
use Fhp\Model\SEPAAccount;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fints;

    protected $guarded = [];

    public function scopeSearch(Builder $query, string $searchtext) : Builder
    {
        if ($searchtext == '') {
            return $query;
        }

        return $query->where( function ($query) use($searchtext)
        {
            $query
                ->orWhere('name', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('blz', 'LIKE', '%' . $searchtext . '%');
        });
    }
}
