<?php

namespace Laravel\Components\Filters;

use Laravel\Components\Http\Requests\NovaRequest;

abstract class BooleanFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'boolean-filter';

    /**
     * Set the default options for the filter.
     *
     * @return array
     */
    public function default()
    {
        return collect($this->options(app(NovaRequest::class)))->values()->mapWithKeys(function ($option) {
            return [$option => false];
        })->all();
    }
}
