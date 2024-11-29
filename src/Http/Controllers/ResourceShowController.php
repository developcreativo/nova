<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\ResourceDetailRequest;
use Laravel\Components\Http\Resources\DetailViewResource;

class ResourceShowController extends Controller
{
    /**
     * Display the resource for administration.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceDetailRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(ResourceDetailRequest $request)
    {
        return DetailViewResource::make()->toResponse($request);
    }
}
