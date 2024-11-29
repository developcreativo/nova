<?php

namespace Laravel\Components\Fields\Filters;

use Laravel\Components\Contracts\FilterableField;
use Laravel\Components\Filters\Filter as BaseFilter;
use Laravel\Components\Http\Requests\NovaRequest;

abstract class Filter extends BaseFilter
{
    /**
     * The filter's field.
     *
     * @var \Laravel\Components\Contracts\FilterableField&\Laravel\Components\Fields\Field
     */
    public $field;

    /**
     * Construct a new filter.
     *
     * @param  \Laravel\Components\Contracts\FilterableField&\Laravel\Components\Fields\Field  $field
     */
    public function __construct(FilterableField $field)
    {
        $this->field = $field;
    }

    /**
     * Get the displayable name of the filter.
     *
     * @return string
     */
    public function name()
    {
        return $this->field->name;
    }

    /**
     * Get the key for the filter.
     *
     * @return string
     */
    public function key()
    {
        return class_basename($this->field).':'.$this->field->attribute;
    }

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
        $this->field->applyFilter($request, $query, $value);

        return $query;
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array
     */
    public function serializeField()
    {
        return $this->field->serializeForFilter();
    }

    /**
     * Prepare the filter for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'component' => 'filter-'.$this->component,
            'field' => $this->serializeField(),
        ]);
    }
}
