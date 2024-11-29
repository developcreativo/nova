<?php

namespace Laravel\Components\Http\Controllers\Pages;

use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Laravel\Components\Http\Requests\ResourceDetailRequest;
use Laravel\Components\Http\Resources\DetailViewResource;
use Laravel\Components\Menu\Breadcrumb;
use Laravel\Components\Menu\Breadcrumbs;
use Laravel\Components\Nova;

class ResourceDetailController extends Controller
{
    /**
     * Show Resource Detail page using Inertia.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceDetailRequest  $request
     * @return \Inertia\Response
     */
    public function __invoke(ResourceDetailRequest $request)
    {
        $resourceClass = $request->resource();

        return Inertia::render('Nova.Detail', [
            'breadcrumbs' => $this->breadcrumbs($request),
            'resourceName' => $resourceClass::uriKey(),
            'resourceId' => $request->resourceId,
        ]);
    }

    /**
     * Get breadcrumb menu for the page.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceDetailRequest  $request
     * @return \Laravel\Components\Menu\Breadcrumbs
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function breadcrumbs(ResourceDetailRequest $request)
    {
        $resource = DetailViewResource::make()->authorizedResourceForRequest($request);

        return Breadcrumbs::make([
            Breadcrumb::make(Nova::__('Resources')),
            Breadcrumb::resource($request->resource()),
            Breadcrumb::make(Nova::__(':resource Details: :title', [
                'resource' => $resource::singularLabel(),
                'title' => $resource->title(),
            ])),
        ]);
    }
}
