<?php

namespace Laravel\Components\Http\Requests;

use Laravel\Components\Metrics\Metric;
use Laravel\Components\Nova;

/**
 * @property-read string $metric
 */
class DashboardMetricRequest extends NovaRequest
{
    /**
     * Get the metric instance for the given request.
     *
     * @return \Laravel\Components\Metrics\Metric
     */
    public function metric()
    {
        return $this->availableMetrics()->first(function ($metric) {
            return $this->metric === $metric->uriKey();
        }) ?: abort(404);
    }

    /**
     * Get all of the possible metrics for the request.
     *
     * @return \Illuminate\Support\Collection
     */
    public function availableMetrics()
    {
        return Nova::allAvailableDashboardCards($this)->whereInstanceOf(Metric::class);
    }
}
