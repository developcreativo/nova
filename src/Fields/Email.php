<?php

namespace Laravel\Components\Fields;

use Illuminate\Support\Arr;
use Laravel\Components\Contracts\FilterableField;
use Laravel\Components\Fields\Filters\TextFilter;
use Laravel\Components\Http\Requests\NovaRequest;

class Email extends Text implements FilterableField
{
    use FieldFilterable, SupportsDependentFields;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'email-field';

    /**
     * Create a new field.
     *
     * @param  string|null  $name
     * @param  string|\Closure|callable|object|null  $attribute
     * @param  (callable(mixed, mixed, ?string):(mixed))|null  $resolveCallback
     * @return void
     */
    public function __construct($name = 'Email', $attribute = 'email', callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);
    }

    /**
     * Make the field filter.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return \Laravel\Components\Fields\Filters\Filter
     */
    protected function makeFilter(NovaRequest $request)
    {
        return tap(new TextFilter($this), function ($filter) {
            $filter->component = 'email-field';
        });
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array
     */
    public function serializeForFilter()
    {
        return transform($this->jsonSerialize(), function ($field) {
            return Arr::only($field, [
                'uniqueKey',
                'name',
                'attribute',
                'type',
                'min',
                'max',
                'step',
                'pattern',
                'placeholder',
                'extraAttributes',
            ]);
        });
    }
}
