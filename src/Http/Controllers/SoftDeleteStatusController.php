<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\NovaRequest;

class SoftDeleteStatusController extends Controller
{
    /**
     * Determine if the resource is soft deleting.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(NovaRequest $request)
    {
        $resource = $request->resource();

        return response()->json(['softDeletes' => $resource::softDeletes()]);
    }
}
