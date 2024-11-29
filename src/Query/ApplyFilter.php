<?php

namespace Laravel\Components\Query;

use Laravel\Components\Http\Requests\NovaRequest;

class ApplyFilter
{
    /**
     * The filter instance.
     *
     * @var \Laravel\Components\Filters\Filter
     */
    public $filter;

    /**
     * The value of the filter.
     *
     * @var mixed
     */
    public $value;

    /**
     * Create a new invokable filter applier.
     *
     * @param  \Laravel\Components\Filters\Filter  $filter
     * @param  mixed  $value
     * @return void
     */
    public function __construct($filter, $value)
    {
        $this->value = $value;
        $this->filter = $filter;
    }

    /**
     * Apply the filter to the given query.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function __invoke(NovaRequest $request, $query)
    {
        $this->filter->apply(
            $request, $query, $this->value
        );

        return $query;
    }
}
