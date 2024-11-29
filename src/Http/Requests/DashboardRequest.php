<?php

namespace Laravel\Components\Http\Requests;

use Laravel\Components\Nova;

class DashboardRequest extends NovaRequest
{
    /**
     * Get all of the possible cards for the request.
     *
     * @param  string  $dashboard
     * @return \Illuminate\Support\Collection
     */
    public function availableCards($dashboard)
    {
        return Nova::availableDashboardCardsForDashboard($dashboard, $this);
    }
}
