<?php

namespace Laravel\Components\Contracts;

use Illuminate\Http\Request;
use Laravel\Components\Http\Requests\NovaRequest;

interface Filter
{
    /**
     * Get the key for the filter.
     *
     * @return string
     */
    public function key();

    /**
     * Apply the filter to the given query.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(NovaRequest $request, $query, $value);

    /**
     * Determine if the filter should be available for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToSee(Request $request);
}
