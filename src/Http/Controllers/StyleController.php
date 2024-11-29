<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\NovaRequest;
use Laravel\Components\Nova;

class StyleController extends Controller
{
    /**
     * Serve the requested stylesheet.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return \Laravel\Components\Style
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function __invoke(NovaRequest $request)
    {
        $asset = collect(Nova::allStyles())
                    ->filter(function ($asset) use ($request) {
                        return $asset->name() === $request->style;
                    })->first();

        abort_if(is_null($asset), 404);

        return $asset;
    }
}
