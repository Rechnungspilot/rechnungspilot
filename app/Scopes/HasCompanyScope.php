<?php

namespace App\Scopes;

use \Illuminate\Database\Eloquent\Builder;
use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Scope;

class HasCompanyScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if($companyId = session('user.company.id')) {
            $builder->where($model->getTable() . '.company_id', $companyId);
        }
    }

    /**
     * Remove the scope from the given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    public function remove(Builder $builder)
    {

    }
}