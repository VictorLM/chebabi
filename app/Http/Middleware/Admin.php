<?php

namespace Intranet\Http\Middleware;

use Closure;

class Admin
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
        if ($request->user()->tipo != 'admin'){
            return abort(403, 'Não autorizado.');
        }else{
            return $next($request);
        }
    }
}
