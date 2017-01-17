<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
      if (Gate::denies('access', $role)) {
          return redirect()
              ->route('home')
              ->with('alert', 'No tienes acceso.');
      }
        return $next($request);
    }

}
