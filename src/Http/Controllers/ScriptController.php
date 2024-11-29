<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\NovaRequest;
use Laravel\Components\Nova;

class ScriptController extends Controller
{
    /**
     * Serve the requested script.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return \Laravel\Components\Script
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function __invoke(NovaRequest $request)
    {
        $asset = collect(Nova::allScripts())
                    ->filter(function ($asset) use ($request) {
                        return $asset->name() === $request->script;
                    })->first();

        abort_if(is_null($asset), 404);

        return $asset;
    }
}
