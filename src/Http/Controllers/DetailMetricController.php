<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\MetricRequest;

class DetailMetricController extends Controller
{
    /**
     * Get the specified metric's value.
     *
     * @param  \Laravel\Components\Http\Requests\MetricRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(MetricRequest $request)
    {
        return response()->json([
            'value' => $request->detailMetric()->resolve($request),
        ]);
    }
}
