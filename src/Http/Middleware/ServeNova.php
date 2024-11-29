<?php

namespace Laravel\Components\Http\Middleware;

use Laravel\Components\Events\NovaServiceProviderRegistered;
use Laravel\Components\Util;

class ServeNova
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
        if (Util::isNovaRequest($request)) {
            NovaServiceProviderRegistered::dispatch();
        }

        return $next($request);
    }
}
