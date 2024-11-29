<?php

namespace Laravel\Components\Http\Controllers\Pages;

use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Laravel\Components\Http\Requests\LensRequest;
use Laravel\Components\Http\Resources\LensViewResource;
use Laravel\Components\Menu\Breadcrumb;
use Laravel\Components\Menu\Breadcrumbs;
use Laravel\Components\Nova;

class LensController extends Controller
{
    /**
     * Show Resource Lens page using Inertia.
     *
     * @param  \Laravel\Components\Http\Requests\LensRequest  $request
     * @return \Inertia\Response
     */
    public function __invoke(LensRequest $request)
    {
        $lens = LensViewResource::make()->authorizedLensForRequest($request);

        return Inertia::render('Nova.Lens', [
            'breadcrumbs' => $this->breadcrumbs($request),
            'resourceName' => $request->route('resource'),
            'lens' => $lens->uriKey(),
            'searchable' => $lens::searchable(),
        ]);
    }

    /**
     * Get breadcrumb menu for the page.
     *
     * @param  \Laravel\Components\Http\Requests\LensRequest  $request
     * @return \Laravel\Components\Menu\Breadcrumbs
     */
    protected function breadcrumbs(LensRequest $request)
    {
        return Breadcrumbs::make([
            Breadcrumb::make(Nova::__('Resources')),
            Breadcrumb::resource($request->resource()),
            Breadcrumb::make($request->lens()->name()),
        ]);
    }
}
