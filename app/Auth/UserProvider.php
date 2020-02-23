<?php

namespace App\Auth;

use App\Scopes\HasCompanyScope;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\UserProvider as UserProviderContract;

class UserProvider extends EloquentUserProvider implements UserProviderContract
{
    /**
     * Get a new query builder for the model instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model|null  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function newModelQuery($model = null)
    {
        return (is_null($model)
                ? $this->createModel()->newQuery()
                : $model->newQuery())->withoutGlobalScope(HasCompanyScope::class);
    }
}