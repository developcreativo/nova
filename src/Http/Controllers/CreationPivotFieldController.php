<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\ResourceCreateOrAttachRequest;
use Laravel\Components\Http\Resources\CreationPivotFieldResource;

class CreationPivotFieldController extends Controller
{
    /**
     * List the pivot fields for the given resource and relation.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceCreateOrAttachRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(ResourceCreateOrAttachRequest $request)
    {
        return CreationPivotFieldResource::make()->toResponse($request);
    }

    /**
     * Synchronize the pivot field for creation.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceCreateOrAttachRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync(ResourceCreateOrAttachRequest $request)
    {
        $resource = CreationPivotFieldResource::make()->newResourceWith($request);

        return response()->json(
            $resource->creationPivotFields(
                $request, $request->relatedResource
            )->filter(function ($field) use ($request) {
                return $request->query('field') === $field->attribute &&
                        $request->query('component') === $field->dependentComponentKey();
            })->applyDependsOn($request)
            ->first()
        );
    }
}
