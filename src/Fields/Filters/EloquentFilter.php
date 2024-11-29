<?php

namespace Laravel\Components\Fields\Filters;

/**
 * @template TField of \Laravel\Components\Contracts\FilterableField&\Laravel\Components\Contracts\RelatableField&\Laravel\Components\Fields\Field
 */
class EloquentFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'eloquent-field';

    /**
     * Get the key for the filter.
     *
     * @return string
     */
    public function key()
    {
        return 'resource:'.$this->field->resourceClass::uriKey().':'.$this->field->attribute;
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array
     */
    public function serializeField()
    {
        if (method_exists($this->field, 'serializeForFilter')) {
            return $this->field->serializeForFilter();
        }

        return $this->field->jsonSerialize();
    }
}
