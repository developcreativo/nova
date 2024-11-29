<?php

namespace Laravel\Components\Http\Middleware;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Laravel\Components\Events\ServingNova;
use Laravel\Components\Http\Requests\NovaRequest;

class DispatchServingNovaEvent
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
        $preventsAccessingMissingAttributes = method_exists(Model::class, 'preventsAccessingMissingAttributes')
            ? Model::preventsAccessingMissingAttributes()
            : null;

        if ($preventsAccessingMissingAttributes === true) {
            Model::preventAccessingMissingAttributes(false);
        }

        ServingNova::dispatch($request);

        Container::getInstance()->forgetInstance(NovaRequest::class);

        $response = $next($request);

        if ($preventsAccessingMissingAttributes === true) {
            Model::preventAccessingMissingAttributes(true);
        }

        return $response;
    }
}
