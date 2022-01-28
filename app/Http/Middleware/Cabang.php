<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\User as UserHelp;
use Illuminate\Http\Request;

class Cabang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (UserHelp::isCabang()) {
            return $next($request);
        }

        abort(404);
    }
}
