<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\DashboardMetricRequest;

class DashboardMetricController extends Controller
{
    /**
     * Get the specified metric's value.
     *
     * @param  \Laravel\Components\Http\Requests\DashboardMetricRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(DashboardMetricRequest $request)
    {
        return response()->json([
            'value' => $request->metric()->resolve($request),
        ]);
    }
}
