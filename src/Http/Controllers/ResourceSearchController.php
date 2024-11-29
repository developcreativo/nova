<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\NovaRequest;
use Laravel\Components\Http\Requests\ResourceSearchRequest;
use Laravel\Components\Resource;
use Laravel\Components\Util;

class ResourceSearchController extends Controller
{
    /**
     * List the resources for administration.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceSearchRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(ResourceSearchRequest $request)
    {
        $resource = $request->resource();

        $withTrashed = $this->shouldIncludeTrashed(
            $request, $resource
        );

        return response()->json([
            'resources' => $request->searchIndex()
                        ->mapInto($resource)
                        ->map(function ($resource) use ($request) {
                            return $this->transformResult($request, $resource);
                        })->values(),
            'softDeletes' => $resource::softDeletes(),
            'withTrashed' => $withTrashed,
        ]);
    }

    /**
     * Determine if the query should include trashed models.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  string  $resource
     * @return bool
     */
    protected function shouldIncludeTrashed(NovaRequest $request, $resource)
    {
        if ($request->withTrashed === 'true') {
            return true;
        }

        $model = $resource::newModel();

        if ($request->current && empty($request->search) && $resource::softDeletes()) {
            $model = $model->newQueryWithoutScopes()->find($request->current);

            return $model ? $model->trashed() : false;
        }

        return false;
    }

    /**
     * Transform the result from resource.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Components\Resource  $resource
     * @return array
     */
    protected function transformResult(NovaRequest $request, Resource $resource)
    {
        return array_filter([
            'avatar' => $resource->resolveAvatarUrl($request),
            'display' => (string) $resource->title(),
            'subtitle' => $resource->subtitle(),
            'value' => Util::safeInt($resource->getKey()),
        ]);
    }
}
