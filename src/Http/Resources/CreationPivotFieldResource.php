<?php

namespace Laravel\Components\Http\Resources;

use Laravel\Components\Http\Requests\ResourceCreateOrAttachRequest;

class CreationPivotFieldResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceCreateOrAttachRequest  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->newResourceWith($request)
                    ->creationPivotFields(
                        $request,
                        $request->relatedResource
                    )->applyDependsOnWithDefaultValues($request)->all();
    }

    /**
     * Get current resource for the request.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceCreateOrAttachRequest  $request
     * @return \Laravel\Components\Resource
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function newResourceWith(ResourceCreateOrAttachRequest $request)
    {
        return tap($request->newResourceWith($request->findModel() ?? $request->model()), function ($resource) use ($request) {
            abort_unless($resource->hasRelatableField($request, $request->viaRelationship), 404);
        });
    }
}
