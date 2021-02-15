<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapAppRoutes();

        $this->mapTimeRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::domain($this->baseDomain(''))
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));

        Route::domain($this->baseDomain('www'))
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));

        Route::domain('https://d15r.de')
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/d15r.php'));

        Route::domain('https://www.d15r.de')
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/d15r.php'));

        Route::domain('https://hof-sundermeier.de')
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/hof-sundermeier.php'));

        Route::domain('https://www.hof-sundermeier.de')
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/hof-sundermeier.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::domain($this->baseDomain('api'))
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    protected function mapAppRoutes()
    {
        Route::domain($this->baseDomain('app'))
             ->middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/app.php'));
    }

    protected function mapTimeRoutes()
    {
        Route::domain($this->baseDomain('zeit'))
             ->middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/time.php'));
    }

    private function baseDomain(string $subdomain = ''): string
    {
        if (strlen($subdomain) > 0) {
            $subdomain = "{$subdomain}.";
        }

        return $subdomain . config('app.base_domain');
    }
}
