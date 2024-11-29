<?php

namespace Laravel\Components\Fields\Repeater\Presets;

use Illuminate\Database\Eloquent\Model;
use Laravel\Components\Fields\Repeater\RepeatableCollection;
use Laravel\Components\Http\Requests\NovaRequest;

interface Preset
{
    /**
     * Save the field value to permanent storage.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  string  $requestAttribute
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $attribute
     * @param  \Laravel\Components\Fields\Repeater\RepeatableCollection  $repeatables
     * @param  string|null  $uniqueField
     * @return \Closure|void
     */
    public function set(NovaRequest $request, string $requestAttribute, Model $model, string $attribute, RepeatableCollection $repeatables, $uniqueField);

    /**
     * Retrieve the value from storage and hydrate the field's value.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $attribute
     * @param  \Laravel\Components\Fields\Repeater\RepeatableCollection  $repeatables
     * @return \Illuminate\Support\Collection
     */
    public function get(NovaRequest $request, Model $model, string $attribute, RepeatableCollection $repeatables);
}
