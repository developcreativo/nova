<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\ResourceCreateOrAttachRequest;
use Laravel\Components\Http\Resources\CreateViewResource;
use Laravel\Components\Http\Resources\ReplicateViewResource;

class CreationFieldSyncController extends Controller
{
    /**
     * Synchronize the field for creation view.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceCreateOrAttachRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(ResourceCreateOrAttachRequest $request)
    {
        $resource = $request->has('fromResourceId')
                        ? ReplicateViewResource::make($request->fromResourceId)->newResourceWith($request)
                        : CreateViewResource::make()->newResourceWith($request);

        return response()->json(
            $resource->creationFields($request)
                ->filter(function ($field) use ($request) {
                    return $request->query('field') === $field->attribute &&
                            $request->query('component') === $field->dependentComponentKey();
                })->each->syncDependsOn($request)
                ->first()
        );
    }
}
