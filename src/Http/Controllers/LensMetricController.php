<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\LensMetricRequest;

class LensMetricController extends Controller
{
    /**
     * List the metrics for the given resource.
     *
     * @param  \Laravel\Components\Http\Requests\LensMetricRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(LensMetricRequest $request)
    {
        return response()->json(
            $request->availableMetrics()
        );
    }

    /**
     * Get the specified metric's value.
     *
     * @param  \Laravel\Components\Http\Requests\LensMetricRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(LensMetricRequest $request)
    {
        return response()->json([
            'value' => $request->metric()->resolve($request),
        ]);
    }
}
