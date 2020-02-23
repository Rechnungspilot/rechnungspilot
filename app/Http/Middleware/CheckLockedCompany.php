<?php

namespace App\Http\Middleware;

use Closure;

class CheckLockedCompany
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->company->locked)
        {
            return redirect('einstellungen/finanzielles')
                ->with('status', 'Der Account ist gesperrt!');
        }

        return $next($request);
    }
}
