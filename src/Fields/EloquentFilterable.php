<?php

namespace Laravel\Components\Fields;

use Laravel\Components\Fields\Filters\EloquentFilter;
use Laravel\Components\Http\Requests\NovaRequest;

trait EloquentFilterable
{
    use Filterable;

    /**
     * Make the field filter.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return \Laravel\Components\Fields\Filters\EloquentFilter|null
     */
    protected function makeFilter(NovaRequest $request)
    {
        return new EloquentFilter($this);
    }

    /**
     * Define filterable attribute.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return string
     */
    abstract protected function filterableAttribute(NovaRequest $request);

    /**
     * Define the default filterable callback.
     *
     * @return callable(\Laravel\Components\Http\Requests\NovaRequest, \Illuminate\Database\Eloquent\Builder, mixed, string):void
     */
    abstract protected function defaultFilterableCallback();
}
