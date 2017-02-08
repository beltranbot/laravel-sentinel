<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;

class ManagerMiddleware
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
      // 1. user should be authenticated
      // 2. authenticated user should be an admin
      if (Sentinel::check() && Sentinel::getUser()->roles->first()->slug == 'manager') {
        return $next($request);
      } else {
        return redirect('/');
      }

    }
}
