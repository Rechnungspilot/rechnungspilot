<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Blade::if('admin', function () {
            return auth()->check() && auth()->user()->isAdmin;
        });

        Validator::extend('formated_number', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[0-9]+,?[0-9]*$/', $value);
        });

        if (! App::runningInConsole()) {
            $root = basename(\Illuminate\Support\Facades\Request::root());
            if ($root == 'd15r.de') {
                Config::set('session.domain', '.d15r.de');
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
