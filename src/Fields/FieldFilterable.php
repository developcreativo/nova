<?php

namespace Laravel\Components\Fields;

use Laravel\Components\Http\Requests\NovaRequest;

trait FieldFilterable
{
    use Filterable;

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array
     */
    public function serializeForFilter()
    {
        return $this->jsonSerialize();
    }

    /**
     * Define the default filterable callback.
     *
     * @return callable(\Laravel\Components\Http\Requests\NovaRequest, \Illuminate\Database\Eloquent\Builder, mixed, string):\Illuminate\Database\Eloquent\Builder
     */
    protected function defaultFilterableCallback()
    {
        return function (NovaRequest $request, $query, $value, $attribute) {
            return $query->where($attribute, '=', $value);
        };
    }

    /**
     * Define filterable attribute.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return string
     */
    protected function filterableAttribute(NovaRequest $request)
    {
        return $this->attribute;
    }
}
