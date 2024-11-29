<?php

namespace Laravel\Components\Fields;

use Illuminate\Support\Arr;
use Laravel\Components\Contracts\Deletable as DeletableContract;
use Laravel\Components\Contracts\FilterableField;
use Laravel\Components\Contracts\Storable as StorableContract;
use Laravel\Components\Fields\Filters\TextFilter;
use Laravel\Components\Http\Requests\NovaRequest;

class Trix extends Field implements FilterableField, StorableContract, DeletableContract
{
    use FieldFilterable,
        HasAttachments,
        Expandable,
        SupportsDependentFields;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'trix-field';

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var bool
     */
    public $showOnIndex = false;

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  string  $requestAttribute
     * @param  \Illuminate\Database\Eloquent\Model|\Laravel\Components\Support\Fluent  $model
     * @param  string  $attribute
     * @return void|\Closure
     */
    protected function fillAttribute(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        return $this->fillAttributeWithAttachment($request, $requestAttribute, $model, $attribute);
    }

    /**
     * Get the full path that the field is stored at on disk.
     *
     * @return string|null
     */
    public function getStoragePath()
    {
        return null;
    }

    /**
     * Make the field filter.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
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
            return Arr::only($field, [
                'uniqueKey',
                'name',
                'attribute',
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
        return array_merge(parent::jsonSerialize(), [
            'shouldShow' => $this->shouldBeExpanded(),
            'withFiles' => $this->withFiles,
        ]);
    }
}
