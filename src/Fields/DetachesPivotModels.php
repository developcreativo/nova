<?php

namespace Laravel\Components\Fields;

use Laravel\Components\Contracts\Deletable;
use Laravel\Components\DeleteField;
use Laravel\Components\Nova;

trait DetachesPivotModels
{
    /**
     * Get the pivot record detachment callback for the field.
     *
     * @return \Closure(\Laravel\Components\Http\Requests\NovaRequest, mixed):bool
     */
    protected function detachmentCallback()
    {
        return function ($request, $model) {
            $pivotAccessor = $model->{$this->attribute}()->getPivotAccessor();

            foreach ($model->{$this->attribute}()->withoutGlobalScopes()->cursor() as $related) {
                $resource = Nova::newResourceFromModel($related);

                $pivot = $related->{$pivotAccessor};

                $pivotFields = $resource->resolvePivotFields($request, $request->resource);

                $pivotFields->whereInstanceOf(Deletable::class)
                        ->filter->isPrunable()
                        ->each(function ($field) use ($request, $pivot) {
                            /** @var \Laravel\Components\Fields\Field&\Laravel\Components\Contracts\Deletable $field */
                            DeleteField::forRequest($request, $field, $pivot)->save();
                        });

                $pivot->delete();
            }

            return true;
        };
    }
}
