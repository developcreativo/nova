<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\LensRequest;
use Laravel\Components\Http\Resources\LensViewResource;

class LensController extends Controller
{
    /**
     * List the lenses for the given resource.
     *
     * @param  \Laravel\Components\Http\Requests\LensRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(LensRequest $request)
    {
        return response()->json(
            $request->availableLenses()
        );
    }

    /**
     * Get the specified lens and its resources.
     *
     * @param  \Laravel\Components\Http\Requests\LensRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(LensRequest $request)
    {
        return LensViewResource::make()->toResponse($request);
    }
}
