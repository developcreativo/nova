<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\ResourceUpdateOrUpdateAttachedRequest;
use Laravel\Components\Http\Resources\UpdateViewResource;

class UpdateFieldController extends Controller
{
    /**
     * List the update fields for the given resource.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceUpdateOrUpdateAttachedRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(ResourceUpdateOrUpdateAttachedRequest $request)
    {
        return UpdateViewResource::make()->toResponse($request);
    }

    /**
     * Synchronize the field for updating.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceUpdateOrUpdateAttachedRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync(ResourceUpdateOrUpdateAttachedRequest $request)
    {
        $resource = UpdateViewResource::make()->newResourceWith($request);

        return response()->json(
            $resource->updateFields($request)
                ->filter(function ($field) use ($request) {
                    return $request->query('field') === $field->attribute &&
                            $request->query('component') === $field->dependentComponentKey();
                })->each->syncDependsOn($request)
                ->first()
        );
    }
}
