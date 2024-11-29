<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\DashboardRequest;

class DashboardCardController extends Controller
{
    /**
     * List the cards for the dashboard.
     *
     * @param  \Laravel\Components\Http\Requests\DashboardRequest  $request
     * @param  string  $dashboard
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(DashboardRequest $request, $dashboard = 'main')
    {
        return response()->json(
            $request->availableCards($dashboard)
        );
    }
}
