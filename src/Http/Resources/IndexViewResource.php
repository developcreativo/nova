<?php

namespace Laravel\Components\Http\Resources;

use Laravel\Components\Http\Requests\ResourceIndexRequest;

class IndexViewResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceIndexRequest  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = $this->authorizedResourceForRequest($request);

        [$paginator, $total, $sortable] = $request->searchIndex();

        return [
            'label' => $resource::label(),
            'resources' => $paginator->getCollection()->mapInto($resource)->map->serializeForIndex($request),
            'prev_page_url' => $paginator->previousPageUrl(),
            'next_page_url' => $paginator->nextPageUrl(),
            'per_page' => $paginator->perPage(),
            'per_page_options' => $resource::perPageOptions(),
            'total' => $total,
            'softDeletes' => $resource::softDeletes(),
            'polling' => $resource::$polling,
            'pollingInterval' => $resource::$pollingInterval * 1000,
            'showPollingToggle' => $resource::$showPollingToggle,
            'sortable' => $sortable ?? true,
        ];
    }

    /**
     * Get authorized resource for the request.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceIndexRequest  $request
     * @return class-string<\Laravel\Components\Resource>
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizedResourceForRequest(ResourceIndexRequest $request)
    {
        return tap($request->resource(), function ($resource) use ($request) {
            abort_unless($resource::authorizedToViewAny($request), 403);
        });
    }
}
