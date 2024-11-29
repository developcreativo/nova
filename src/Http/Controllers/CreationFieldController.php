<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\ResourceCreateOrAttachRequest;
use Laravel\Components\Http\Resources\CreateViewResource;
use Laravel\Components\Http\Resources\ReplicateViewResource;

class CreationFieldController extends Controller
{
    /**
     * List the creation fields for the given resource.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceCreateOrAttachRequest  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(ResourceCreateOrAttachRequest $request)
    {
        if ($request->has('fromResourceId')) {
            return ReplicateViewResource::make($request->fromResourceId)->toResponse($request);
        }

        return CreateViewResource::make()->toResponse($request);
    }
}
