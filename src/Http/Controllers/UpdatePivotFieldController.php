<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\ResourceUpdateOrUpdateAttachedRequest;
use Laravel\Components\Http\Resources\UpdatePivotFieldResource;

class UpdatePivotFieldController extends Controller
{
    /**
     * List the pivot fields for the given resource and relation.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceUpdateOrUpdateAttachedRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(ResourceUpdateOrUpdateAttachedRequest $request)
    {
        return UpdatePivotFieldResource::make()->toResponse($request);
    }

    /**
     * Synchronize the pivot field for updating.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceUpdateOrUpdateAttachedRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync(ResourceUpdateOrUpdateAttachedRequest $request)
    {
        $resource = UpdatePivotFieldResource::make()->newResourceWith($request);

        return response()->json(
            $resource->updatePivotFields(
                $request, $request->relatedResource
            )->filter(function ($field) use ($request) {
                return $request->query('field') === $field->attribute &&
                        $request->query('component') === $field->dependentComponentKey();
            })->applyDependsOn($request)
            ->first()
        );
    }
}
