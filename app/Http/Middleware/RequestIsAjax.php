<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequestIsAjax
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!$request->ajax()) {
            return redirect()->route('login.index');
        }

        return $next($request);
    }
}
