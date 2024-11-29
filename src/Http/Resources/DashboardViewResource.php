<?php

namespace Laravel\Components\Http\Resources;

use Laravel\Components\Dashboards\Main;
use Laravel\Components\Http\Requests\DashboardRequest;
use Laravel\Components\Nova;

class DashboardViewResource extends Resource
{
    /**
     * The dashboard name.
     *
     * @var string
     */
    protected $name;

    /**
     * Construct a new Dashboard Resource.
     *
     * @param  string  $name
     * @return void
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Laravel\Components\Http\Requests\DashboardRequest  $request
     * @return array
     */
    public function toArray($request)
    {
        $dashboard = $this->authorizedDashboardForRequest($request);

        return [
            'label' => $dashboard->label(),
            'cards' => $request->availableCards($this->name),
            'showRefreshButton' => $dashboard->showRefreshButton,
            'isHelpCard' => $dashboard instanceof Main,
        ];
    }

    /**
     * Get authorized dashboard for the request.
     *
     * @param  \Laravel\Components\Http\Requests\DashboardRequest  $request
     * @return \Laravel\Components\Dashboard
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizedDashboardForRequest(DashboardRequest $request)
    {
        return tap(Nova::dashboardForKey($this->name, $request), function ($dashboard) {
            abort_if(is_null($dashboard), 404);
        });
    }
}
