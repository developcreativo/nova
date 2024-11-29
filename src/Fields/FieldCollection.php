<?php

namespace Laravel\Components\Fields;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Laravel\Components\Contracts\FilterableField;
use Laravel\Components\Contracts\ListableField;
use Laravel\Components\Contracts\RelatableField;
use Laravel\Components\Contracts\Resolvable;
use Laravel\Components\Http\Requests\NovaRequest;
use Laravel\Components\Panel;
use Laravel\Components\ResourceTool;
use Laravel\Components\ResourceToolElement;
use Laravel\Components\Util;

/**
 * @template TKey of int
 * @template TValue of \Laravel\Components\Panel|\Laravel\Components\ResourceToolElement|\Laravel\Components\Fields\Field|\Illuminate\Http\Resources\MissingValue
 *
 * @extends \Illuminate\Support\Collection<TKey, TValue>
 */
class FieldCollection extends Collection
{
    /**
     * Assign the fields with the given panels to their parent panel.
     *
     * @param  string  $label
     * @return static<TKey, TValue>
     */
    public function assignDefaultPanel($label)
    {
        new Panel($label, $this->reject(function ($field) {
            return isset($field->panel);
        }));

        return $this;
    }

    /**
     * Flatten stacked fields.
     *
     * @return static<int, TValue>
     */
    public function flattenStackedFields()
    {
        return $this->map(function ($field) {
            if ($field instanceof Stack) {
                return $field->fields()->all();
            }

            return $field;
        })->flatten();
    }

    /**
     * Find a given field by its attribute.
     *
     * @template TGetDefault
     *
     * @param  string  $attribute
     * @param  TGetDefault|\Closure():TGetDefault  $default
     * @return TValue|TGetDefault
     */
    public function findFieldByAttribute($attribute, $default = null)
    {
        return $this->first(function ($field) use ($attribute) {
            return isset($field->attribute) &&
                $field->attribute == $attribute;
        }, $default);
    }

    /**
     * Filter elements should be displayed for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return static<int, TValue>
     */
    public function authorized(Request $request)
    {
        return $this->filter(function ($field) use ($request) {
            return $field->authorize($request);
        })->values();
    }

    /**
     * Filter elements should be displayed for the given request.
     *
     * @param  mixed  $resource
     * @return static<int, TValue>
     */
    public function resolve($resource)
    {
        return $this->each(function ($field) use ($resource) {
            if ($field instanceof Resolvable) {
                $field->resolve($resource);
            }
        });
    }

    /**
     * Resolve value of fields for display.
     *
     * @param  mixed  $resource
     * @return static<int, TValue>
     */
    public function resolveForDisplay($resource)
    {
        return $this->each(function ($field) use ($resource) {
            if ($field instanceof ListableField || ! $field instanceof Resolvable) {
                return;
            }

            if ($field->pivot) {
                $field->resolveForDisplay($resource->{$field->pivotAccessor} ?? new Pivot);
            } else {
                $field->resolveForDisplay($resource);
            }
        });
    }

    /**
     * Remove non-creation fields from the collection.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  mixed  $resource
     * @return static<int, \Laravel\Components\Fields\Field>
     */
    public function onlyCreateFields(NovaRequest $request, $resource)
    {
        return $this->reject(function ($field) use ($resource, $request) {
            return $field instanceof ListableField ||
                ($field instanceof ResourceTool || $field instanceof ResourceToolElement) ||
                $field->attribute === 'ComputedField' ||
                ($field instanceof ID && $field->attribute === $resource->getKeyName()) ||
                ! $field->isShownOnCreation($request);
        });
    }

    /**
     * Remove non-update fields from the collection.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  mixed  $resource
     * @return static<int, \Laravel\Components\Fields\Field>
     */
    public function onlyUpdateFields(NovaRequest $request, $resource)
    {
        return $this->reject(function ($field) use ($resource, $request) {
            return $field instanceof ListableField ||
                ($field instanceof ResourceTool || $field instanceof ResourceToolElement) ||
                $field->attribute === 'ComputedField' ||
                ($field instanceof ID && $field->attribute === $resource->getKeyName()) ||
                ! $field->isShownOnUpdate($request, $resource);
        });
    }

    /**
     * Filter fields for showing on detail.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  mixed  $resource
     * @return static<int, \Laravel\Components\Fields\Field>
     */
    public function filterForDetail(NovaRequest $request, $resource)
    {
        return $this->filter(function ($field) use ($resource, $request) {
            return $field->isShownOnDetail($request, $resource);
        })->values();
    }

    /**
     * Filter fields for showing on preview.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  mixed  $resource
     * @return static<int, \Laravel\Components\Fields\Field>
     */
    public function filterForPreview(NovaRequest $request, $resource)
    {
        return $this->filter(function (Field $field) use ($resource, $request) {
            return $field->isShownOnPreview($request, $resource);
        })->values();
    }

    /**
     * Filter fields for showing when peeking.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return static<int, \Laravel\Components\Fields\Field>
     */
    public function filterForPeeking(NovaRequest $request)
    {
        return $this
            ->filter(function (Field $field) use ($request) {
                return $field->isShownWhenPeeking($request);
            })->values();
    }

    /**
     * Filter fields for showing on index.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  mixed  $resource
     * @return static<int, \Laravel\Components\Fields\Field>
     */
    public function filterForIndex(NovaRequest $request, $resource)
    {
        return $this->filter(function ($field) use ($resource, $request) {
            return $field->isShownOnIndex($request, $resource);
        })->values();
    }

    /**
     * Reject if the field is readonly.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return static<int, TValue>
     */
    public function withoutReadonly(NovaRequest $request)
    {
        return $this->reject(function ($field) use ($request) {
            return $field->isReadonly($request);
        });
    }

    /**
     * Reject if the field is a missing value.
     *
     * @return static<int, \Laravel\Components\Panel|\Laravel\Components\ResourceToolElement|\Laravel\Components\Fields\Field>
     */
    public function withoutMissingValues()
    {
        return $this->reject(function ($field) {
            return $field instanceof MissingValue;
        });
    }

    /**
     * Reject fields which use their own index listings.
     *
     * @return static<int, TValue>
     */
    public function withoutListableFields()
    {
        return $this->reject(function ($field) {
            return $field instanceof ListableField;
        });
    }

    /**
     * Reject if the field is unfillable.
     *
     * @return static<int, TValue>
     */
    public function withoutUnfillable()
    {
        return $this->reject(function ($field) {
            return $field instanceof Unfillable;
        });
    }

    /**
     * Reject fields which are actually ResourceTools.
     *
     * @return static<int, TValue>
     */
    public function withoutResourceTools()
    {
        return $this->reject(function ($field) {
            return $field instanceof ResourceToolElement;
        });
    }

    /**
     * Filter the fields to only many-to-many relationships.
     *
     * @return static<TKey, \Laravel\Components\Fields\MorphToMany|\Laravel\Components\Fields\BelongsToMany>
     */
    public function filterForManyToManyRelations()
    {
        return $this->filter(function ($field) {
            return $field instanceof BelongsToMany || $field instanceof MorphToMany;
        });
    }

    /**
     * Reject if the field supports Filterable Field.
     *
     * @return static<TKey, \Laravel\Components\Fields\Field&\Laravel\Components\Contracts\FilterableField>
     */
    public function withOnlyFilterableFields()
    {
        return $this->filter(function ($field) {
            return $field instanceof FilterableField && $field->attribute !== 'ComputedField';
        });
    }

    /**
     * Apply depends on for the request.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return $this
     */
    public function applyDependsOn(NovaRequest $request)
    {
        $this->each->applyDependsOn($request);

        return $this;
    }

    /**
     * Apply depends on for the request with default values.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return $this
     */
    public function applyDependsOnWithDefaultValues(NovaRequest $request)
    {
        $payloads = new LazyCollection(function () use ($request) {
            foreach ($this->items as $field) {
                $key = $field instanceof RelatableField ? $field->relationshipName() : $field->attribute;

                if ($field instanceof MorphTo) {
                    yield "{$key}_type" => $field->morphToType;
                }

                yield $key => Util::hydrate($field->resolveDependentValue($request));
            }
        });

        $this->each->applyDependsOn(
            NovaRequest::createFrom($request)->mergeIfMissing($payloads->all())
        );

        return $this;
    }
}
