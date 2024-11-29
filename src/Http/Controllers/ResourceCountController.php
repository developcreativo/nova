<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\ResourceIndexRequest;

class ResourceCountController extends Controller
{
    /**
     * Get the resource count for a given query.
     *
     * @param  \Laravel\Components\Http\Requests\ResourceIndexRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(ResourceIndexRequest $request)
    {
        return response()->json(['count' => $request->toCount()]);
    }
}
