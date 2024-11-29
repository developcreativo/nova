<?php

namespace Laravel\Components\Http\Resources;

use Laravel\Components\Contracts\ListableField;
use Laravel\Components\Contracts\RelatableField;
use Laravel\Components\Fields\BelongsTo;
use Laravel\Components\Fields\FieldCollection;
use Laravel\Components\Fields\HasOne;
use Laravel\Components\Fields\MorphOne;
use Laravel\Components\Fields\MorphTo;
use Laravel\Components\Http\Requests\ResourceDetailRequest;

class DetailViewResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceDetailRequest  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = $this->authorizedResourceForRequest($request);

        $payload = with($resource->serializeForDetail($request, $resource), function ($detail) use ($request) {
            $detail['fields'] = collect($detail['fields'])
                ->when($request->viaResource, function ($fields) use ($request) {
                    return $fields->reject(function ($field) use ($request) {
                        /** @var \Laravel\Components\Fields\Field $field */
                        if ($field instanceof ListableField) {
                            return true;
                        } elseif (! $field instanceof RelatableField) {
                            return false;
                        }

                        $relatedResource = $field->resourceName == $request->viaResource;

                        return ($request->relationshipType === 'hasOne' && $field instanceof BelongsTo && $relatedResource) ||
                            ($request->relationshipType === 'morphOne' && $field instanceof MorphTo && $relatedResource) ||
                            (in_array($request->relationshipType, ['hasOne', 'morphOne']) && ($field instanceof MorphOne || $field instanceof HasOne));
                    });
                })
                ->values()->all();

            return $detail;
        });

        return [
            'title' => (string) $resource->title(),
            'panels' => $resource->availablePanelsForDetail($request, $resource, FieldCollection::make($payload['fields'])),
            'resource' => $payload,
        ];
    }

    /**
     * Get authorized resource for the request.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceDetailRequest  $request
     * @return \Laravel\Components\Resource
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function authorizedResourceForRequest(ResourceDetailRequest $request)
    {
        return tap($request->newResourceWith(
            tap($request->findModelQuery(), function ($query) use ($request) {
                $resource = $request->resource();
                $resource::detailQuery($request, $query);
            })->firstOrFail()
        ))->authorizeToView($request);
    }
}
