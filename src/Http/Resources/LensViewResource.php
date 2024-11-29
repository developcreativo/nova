<?php

namespace Laravel\Components\Http\Resources;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Components\Http\Requests\LensRequest;
use Laravel\Components\Query\ApplySoftDeleteConstraint;

class LensViewResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Laravel\Components\Http\Requests\LensRequest  $request
     * @return array
     */
    public function toArray($request)
    {
        $lens = $this->authorizedLensForRequest($request);

        $query = $request->newSearchQuery();

        if ($request->resourceSoftDeletes()) {
            (new ApplySoftDeleteConstraint)->__invoke($query, $request->trashed);
        }

        $paginator = $lens->query($request, $query);

        if ($paginator instanceof Builder) {
            $paginator = $paginator->simplePaginate($request->perPage());
        }

        return [
            'name' => $lens->name(),
            'resources' => $resources = $request->toResources($paginator->getCollection()),
            'prev_page_url' => $paginator->previousPageUrl(),
            'next_page_url' => $paginator->nextPageUrl(),
            'per_page' => $paginator->perPage(),
            'per_page_options' => $request->resource()::perPageOptions(),
            'softDeletes' => $request->resourceSoftDeletes(),
            'hasId' => $resources->pluck('id')
                        ->reject(function ($field) {
                            return is_null($field->value);
                        })->isNotEmpty(),
            'polling' => $lens::$polling,
            'pollingInterval' => $lens::$pollingInterval * 1000,
            'showPollingToggle' => $lens::$showPollingToggle,
        ];
    }

    /**
     * Get authorized resource for the request.
     *
     * @param  \Laravel\Components\Http\Requests\LensRequest  $request
     * @return \Laravel\Components\Lenses\Lens
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizedLensForRequest(LensRequest $request)
    {
        return $request->lens();
    }
}
