<?php

namespace Laravel\Components\Http\Controllers\Pages;

use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Laravel\Components\Http\Requests\ResourceCreateOrAttachRequest;
use Laravel\Components\Menu\Breadcrumb;
use Laravel\Components\Menu\Breadcrumbs;
use Laravel\Components\Nova;

class ResourceReplicateController extends Controller
{
    /**
     * Show Resource Replicate page using Inertia.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceCreateOrAttachRequest  $request
     * @return \Inertia\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function __invoke(ResourceCreateOrAttachRequest $request)
    {
        abort_unless($request->findModelQuery()->exists(), 404);

        $resourceClass = $request->resource();

        return Inertia::render('Nova.Replicate', [
            'breadcrumbs' => $this->breadcrumbs($request),
            'resourceName' => $resourceClass::uriKey(),
            'resourceId' => $request->resourceId,
            'viaResource' => $request->query('viaResource') ?? '',
            'viaResourceId' => $request->query('viaResourceId') ?? '',
            'viaRelationship' => $request->query('viaRelationship') ?? '',
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

        return Breadcrumbs::make([
            Breadcrumb::make(Nova::__('Resources')),
            Breadcrumb::resource($resourceClass),
            Breadcrumb::resource($request->findResourceOrFail()),
            Breadcrumb::make(Nova::__('Replicate :resource', ['resource' => $resourceClass::singularLabel()])),
        ]);
    }
}
