<?php

namespace Laravel\Components\Metrics;

use Illuminate\Support\Collection;
use Laravel\Components\Filters\FilterDecoder;
use Laravel\Components\Http\Requests\NovaRequest;

trait ResolvesFilters
{
    /**
     * Filters for the metric.
     *
     * @var \Illuminate\Support\Collection|null
     */
    protected $filters;

    /**
     * Set filters for current metric.
     *
     * @param  \Illuminate\Support\Collection  $filters
     * @return $this
     */
    public function setAvailableFilters(Collection $filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * Apply filter query.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function applyFilterQuery(NovaRequest $request, $query)
    {
        if ($this->filters instanceof Collection) {
            (new FilterDecoder($request->filter, $this->filters))
                ->filters()
                ->each
                ->__invoke($request, $query);
        }

        return $query;
    }
}
