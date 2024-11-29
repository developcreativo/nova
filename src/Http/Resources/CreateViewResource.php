<?php

namespace Laravel\Components\Http\Resources;

use Laravel\Components\Http\Requests\ResourceCreateOrAttachRequest;

class CreateViewResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceCreateOrAttachRequest  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = $this->newResourceWith($request);

        $fields = $resource->creationFieldsWithinPanels($request)->applyDependsOnWithDefaultValues($request);

        return [
            'fields' => $fields,
            'panels' => $resource->availablePanelsForCreate($request, $fields),
        ];
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
        $resourceClass = $request->resource();

        $resourceClass::authorizeToCreate($request);

        return $request->newResource();
    }
}
