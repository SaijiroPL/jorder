<?php

namespace App\Http\Middleware;
use http\Url;
use Illuminate\Support\Facades\Route;

use Closure;

class CheckAdmin
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
        $route = Route::currentRouteName();
        if(session('role', '') != "admin"){
            session(['redirectRoute' => $route]);
            return redirect()->route('admin.check');
        }
        return $next($request);
    }
}
