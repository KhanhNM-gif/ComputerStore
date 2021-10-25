<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $arrKey)
    {
        $keys = explode('|', $arrKey);
        if (!request()->has($keys)) return abort(403, 'Missing parameters');

        return $next($request);
    }
}