<?php

namespace XADMIN\LaravelCmf\Http\Middleware;

use Closure;
use XADMIN\LaravelCmf\Facades\LaravelCmf;

class LaravelCmfAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!app('LaravelCmfAuth')->guest()) {
            $user = app('LaravelCmfAuth')->user();
            app()->setLocale($user->locale ?? app()->getLocale());

            return $user->hasPermission('browse_admin') ? $next($request) : redirect('/');
        }

        $urlLogin = route('laravel-cmf.login');

        return redirect()->guest($urlLogin);
    }
}
