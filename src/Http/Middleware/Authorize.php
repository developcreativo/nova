<?php

namespace Laravel\Components\Http\Middleware;

use Laravel\Components\Nova;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request):mixed  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return Nova::check($request) ? $next($request) : abort(403);
    }
}
