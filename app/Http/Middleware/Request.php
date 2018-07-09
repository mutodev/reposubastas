<?php

namespace App\Http\Middleware;

use Closure;

class Request
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
        $_SERVER['HTTPS'] = 'off';
        $request->server->set('HTTPS', 'off');

        return $next($request);
    }
}
