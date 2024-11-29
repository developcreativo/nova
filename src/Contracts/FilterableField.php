<?php

namespace Laravel\Components\Contracts;

use Laravel\Components\Http\Requests\NovaRequest;

/**
 * @mixin \Laravel\Components\Fields\Field
 *
 * @method array jsonSerialize()
 *
 * @property string $attribute
 * @property callable|null $filterableCallback
 * @property string $name
 * @property string $resourceClass
 */
interface FilterableField
{
    /**
     * Apply the filter to the given query.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return void
     */
    public function applyFilter(NovaRequest $request, $query, $value);

    /**
     * Make the field filter.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return \Laravel\Components\Fields\Filters\Filter|null
     */
    public function resolveFilter(NovaRequest $request);

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array
     */
    public function serializeForFilter();
}
