<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\LensCountRequest;

class LensResourceCountController extends Controller
{
    /**
     * Get the resource count for a given query.
     *
     * @param  \Laravel\Components\Http\Requests\LensCountRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(LensCountRequest $request)
    {
        return response()->json(['count' => $request->toCount()]);
    }
}
