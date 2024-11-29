<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\ResourcePreviewRequest;

class ResourcePreviewController extends Controller
{
    /**
     * Preview the resource for administration.
     *
     * @param  \Laravel\Components\Http\Requests\ResourcePreviewRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(ResourcePreviewRequest $request)
    {
        $resource = $request->newResourceWith(tap($request->findModelQuery(), function ($query) use ($request) {
            $resource = $request->resource();
            $resource::detailQuery($request, $query);
        })->firstOrFail());

        $resource->authorizeToView($request);

        return response()->json([
            'title' => (string) $resource->title(),
            'resource' => $resource->serializeForPreview($request),
        ]);
    }
}
