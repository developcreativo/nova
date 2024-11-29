<?php

namespace Laravel\Components\Fields\Filters;

use Laravel\Components\Http\Requests\NovaRequest;

class NumberFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'number-field';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        $value = collect($value)->transform(function ($value) {
            return ! $this->field->isValidNullValue($value) ? $value : null;
        });

        if ($value->filter()->isNotEmpty()) {
            $this->field->applyFilter($request, $query, $value->all());
        }

        return $query;
    }

    /**
     * Get the default options for the filter.
     *
     * @return array|mixed
     */
    public function default()
    {
        return [null, null];
    }
}
