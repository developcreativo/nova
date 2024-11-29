<?php

namespace Laravel\Components\Http\Requests;

use Laravel\Components\Metrics\Metric;

class LensMetricRequest extends MetricRequest
{
    use InteractsWithLenses;

    /**
     * Get all of the possible metrics for the request.
     *
     * @return \Illuminate\Support\Collection
     */
    public function availableMetrics()
    {
        return $this->lens()->availableCards($this)
                ->whereInstanceOf(Metric::class);
    }
}
