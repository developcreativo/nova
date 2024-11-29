<?php

namespace Laravel\Components\Http\Controllers\Pages;

use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Laravel\Components\Http\Requests\ResourceIndexRequest;
use Laravel\Components\Http\Resources\IndexViewResource;
use Laravel\Components\Menu\Breadcrumb;
use Laravel\Components\Menu\Breadcrumbs;
use Laravel\Components\Nova;

class ResourceIndexController extends Controller
{
    /**
     * Show Resource Index page using Inertia.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceIndexRequest  $request
     * @return \Inertia\Response
     */
    public function __invoke(ResourceIndexRequest $request)
    {
        $resourceClass = IndexViewResource::make()->authorizedResourceForRequest($request);

        return Inertia::render('Nova.Index', [
            'breadcrumbs' => $this->breadcrumbs($request),
            'resourceName' => $resourceClass::uriKey(),
        ]);
    }

    /**
     * Get breadcrumb menu for the page.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceIndexRequest  $request
     * @return \Laravel\Components\Menu\Breadcrumbs
     */
    protected function breadcrumbs(ResourceIndexRequest $request)
    {
        return Breadcrumbs::make([
            Breadcrumb::make(Nova::__('Resources')),
            Breadcrumb::resource($request->resource()),
        ]);
    }
}
