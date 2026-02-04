<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetCurrency
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If it's a web request and session has no currency, set default
        if (!$request->is('api/*') && !Session::has('currency')) {
            Session::put('currency', 'USD');
        }

        return $next($request);
    }
}
