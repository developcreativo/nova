<?php

namespace Laravel\Components\Http\Controllers\Pages;

use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Laravel\Components\Http\Requests\ResourceCreateOrAttachRequest;
use Laravel\Components\Menu\Breadcrumb;
use Laravel\Components\Menu\Breadcrumbs;
use Laravel\Components\Nova;

class AttachableController extends Controller
{
    /**
     * Show Resource Attach page using Inertia.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceCreateOrAttachRequest  $request
     * @return \Inertia\Response
     */
    public function __invoke(ResourceCreateOrAttachRequest $request)
    {
        $resourceClass = $request->resource();

        $isPolymorphic = function ($query) {
            return is_null($query) || in_array($query, [true, 1, '1']);
        };

        $parentResource = $request->findResourceOrFail();

        return Inertia::render('Nova.Attach', [
            'breadcrumbs' => $this->breadcrumbs($request),
            'resourceName' => $resourceClass::uriKey(),
            'resourceId' => $request->resourceId,
            'relatedResourceName' => $request->relatedResource,
            'viaRelationship' => $request->query('viaRelationship'),
            'polymorphic' => $isPolymorphic($request->query('polymorphic')),
            'viaResource' => $parentResource::uriKey(),
            'viaResourceId' => $parentResource->resource->getKey(),
            'parentResource' => [
                'name' => $parentResource->singularLabel(),
                'display' => $parentResource->title(),
            ],
        ]);
    }

    /**
     * Get breadcrumb menu for the page.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceCreateOrAttachRequest  $request
     * @return \Laravel\Components\Menu\Breadcrumbs
     */
    protected function breadcrumbs(ResourceCreateOrAttachRequest $request)
    {
        $resourceClass = $request->resource();
        $relatedResourceClass = $request->relatedResource();

        return Breadcrumbs::make([
            Breadcrumb::make(Nova::__('Resources')),
            Breadcrumb::resource($resourceClass),
            Breadcrumb::resource($request->findResourceOrFail()),
            Breadcrumb::make(Nova::__('Attach :resource', ['resource' => $relatedResourceClass::singularLabel()])),
        ]);
    }
}
