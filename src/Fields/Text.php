<?php

namespace Laravel\Components\Fields;

use Illuminate\Support\Arr;
use Laravel\Components\Contracts\FilterableField;
use Laravel\Components\Fields\Filters\TextFilter;
use Laravel\Components\Http\Requests\NovaRequest;

class Text extends Field implements FilterableField
{
    use AsHTML;
    use Copyable;
    use FieldFilterable;
    use HasSuggestions;
    use SupportsDependentFields;
    use SupportsMaxlength;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'text-field';

    /**
     * Make the field filter.
     *
     * @return \Laravel\Components\Fields\Filters\Filter
     */
    protected function makeFilter(NovaRequest $request)
    {
        return new TextFilter($this);
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array
     */
    public function serializeForFilter()
    {
        return transform($this->jsonSerialize(), function ($field) {
            $field['suggestions'] = $field['suggestions'] ?? $this->resolveSuggestions(app(NovaRequest::class));

            return Arr::only($field, [
                'uniqueKey',
                'name',
                'attribute',
                'suggestions',
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

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $request = app(NovaRequest::class);

        if ($request->isFormRequest()) {
            return array_merge(parent::jsonSerialize(), [
                'suggestions' => $this->resolveSuggestions($request),
            ]);
        }

        return array_merge(parent::jsonSerialize(), [
            'asHtml' => $this->asHtml,
            'copyable' => $this->copyable,
        ]);
    }
}
