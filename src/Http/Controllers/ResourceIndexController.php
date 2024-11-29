<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\ResourceIndexRequest;
use Laravel\Components\Http\Resources\IndexViewResource;

class ResourceIndexController extends Controller
{
    /**
     * List the resources for administration.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceIndexRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(ResourceIndexRequest $request)
    {
        return IndexViewResource::make()->toResponse($request);
    }
}
