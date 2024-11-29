<?php

namespace Laravel\Components\Fields;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Components\Contracts\Deletable as DeletableContract;
use Laravel\Components\Contracts\FilterableField;
use Laravel\Components\Contracts\ListableField;
use Laravel\Components\Contracts\PivotableField;
use Laravel\Components\Contracts\QueryBuilder;
use Laravel\Components\Contracts\RelatableField;
use Laravel\Components\Fields\Filters\EloquentFilter;
use Laravel\Components\Http\Requests\NovaRequest;
use Laravel\Components\Panel;
use Laravel\Components\Rules\RelatableAttachment;
use Laravel\Components\TrashedStatus;

/**
 * @method static static make(mixed $name, string|null $attribute = null, string|null $resource = null)
 */
class BelongsToMany extends Field implements DeletableContract, FilterableField, ListableField, PivotableField, RelatableField
{
    use AttachableRelation,
        Deletable,
        DeterminesIfCreateRelationCanBeShown,
        DetachesPivotModels,
        EloquentFilterable,
        FormatsRelatableDisplayValues,
        ManyToManyCreationRules,
        Searchable,
        Collapsable;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'belongs-to-many-field';

    /**
     * The class name of the related resource.
     *
     * @var class-string<\Laravel\Components\Resource>
     */
    public $resourceClass;

    /**
     * The URI key of the related resource.
     *
     * @var string
     */
    public $resourceName;

    /**
     * The name of the Eloquent "belongs to many" relationship.
     *
     * @var string
     */
    public $manyToManyRelationship;

    /**
     * The callback that should be used to resolve the pivot fields.
     *
     * @var callable(\Laravel\Components\Http\Requests\NovaRequest, \Illuminate\Database\Eloquent\Model):array<int, \Laravel\Components\Fields\Field>
     */
    public $fieldsCallback;

    /**
     * The callback that should be used to resolve the pivot actions.
     *
     * @var callable(\Laravel\Components\Http\Requests\NovaRequest):array<int, \Laravel\Components\Actions\Action>
     */
    public $actionsCallback;

    /**
     * The displayable name that should be used to refer to the pivot class.
     *
     * @var string
     */
    public $pivotName;

    /**
     * The displayable singular label of the relation.
     *
     * @var string|null
     */
    public $singularLabel;

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|null  $attribute
     * @param  class-string<\Laravel\Components\Resource>|null  $resource
     * @return void
     */
    public function __construct($name, $attribute = null, $resource = null)
    {
        parent::__construct($name, $attribute);

        $resource = $resource ?? ResourceRelationshipGuesser::guessResource($name);

        $this->resourceClass = $resource;
        $this->resourceName = $resource::uriKey();
        $this->manyToManyRelationship = $this->attribute = $attribute ?? ResourceRelationshipGuesser::guessRelation($name);
        $this->deleteCallback = $this->detachmentCallback();

        $this->fieldsCallback = function () {
            return [];
        };

        $this->actionsCallback = function () {
            return [];
        };

        $this->noDuplicateRelations();
    }

    /**
     * Get the relationship name.
     *
     * @return string
     */
    public function relationshipName()
    {
        return $this->manyToManyRelationship;
    }

    /**
     * Get the relationship type.
     *
     * @return string
     */
    public function relationshipType()
    {
        return 'belongsToMany';
    }

    /**
     * Determine if the field should be displayed for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorize(Request $request)
    {
        return call_user_func(
            [$this->resourceClass, 'authorizedToViewAny'], $request
        ) && parent::authorize($request);
    }

    /**
     * Resolve the field's value.
     *
     * @param  mixed  $resource
     * @param  string|null  $attribute
     * @return void
     */
    public function resolve($resource, $attribute = null)
    {
        //
    }

    /**
     * Get the validation rules for this field.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function getRules(NovaRequest $request)
    {
        $query = $this->buildAttachableQuery(
            $request, $request->{$this->attribute.'_trashed'} === 'true'
        )->toBase();

        return array_merge_recursive(parent::getRules($request), [
            $this->attribute => ['required', new RelatableAttachment($request, $query)],
        ]);
    }

    /**
     * Get the creation rules for this field.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function getCreationRules(NovaRequest $request)
    {
        return array_merge_recursive(parent::getCreationRules($request), [
            $this->attribute => array_filter($this->getManyToManyCreationRules($request)),
        ]);
    }

    /**
     * Build an attachable query for the field.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  bool  $withTrashed
     * @return \Laravel\Components\Contracts\QueryBuilder
     */
    public function buildAttachableQuery(NovaRequest $request, $withTrashed = false)
    {
        $model = forward_static_call([$resourceClass = $this->resourceClass, 'newModel']);

        $query = app()->make(QueryBuilder::class, [$resourceClass]);

        $request->first === 'true'
                        ? $query->whereKey($model->newQueryWithoutScopes(), $request->current)
                        : $query->search(
                            $request, $model->newQuery(), $request->search,
                            [], [], TrashedStatus::fromBoolean($withTrashed)
                        );

        return $query->tap(function ($query) use ($request, $model) {
            forward_static_call($this->attachableQueryCallable($request, $model), $request, $query, $this);
        });
    }

    /**
     * Get the attachable query method name.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return array
     */
    protected function attachableQueryCallable(NovaRequest $request, $model)
    {
        return ($method = $this->attachableQueryMethod($request, $model))
                    ? [$request->resource(), $method]
                    : [$this->resourceClass, 'relatableQuery'];
    }

    /**
     * Get the attachable query method name.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return string|null
     */
    protected function attachableQueryMethod(NovaRequest $request, $model)
    {
        $method = 'relatable'.Str::plural(class_basename($model));

        if (method_exists($request->resource(), $method)) {
            return $method;
        }
    }

    /**
     * Format the given attachable resource.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  mixed  $resource
     * @return array
     */
    public function formatAttachableResource(NovaRequest $request, $resource)
    {
        return array_filter([
            'avatar' => $resource->resolveAvatarUrl($request),
            'display' => $this->formatDisplayValue($resource),
            'subtitle' => $resource->subtitle(),
            'value' => $resource->getKey(),
        ]);
    }

    /**
     * Specify the callback to be executed to retrieve the pivot fields.
     *
     * @param  callable(\Laravel\Components\Http\Requests\NovaRequest, \Illuminate\Database\Eloquent\Model):array<int, \Laravel\Components\Fields\Field>  $callback
     * @return $this
     */
    public function fields($callback)
    {
        $this->fieldsCallback = $callback;

        return $this;
    }

    /**
     * Specify the callback to be executed to retrieve the pivot actions.
     *
     * @param  callable(\Laravel\Components\Http\Requests\NovaRequest):array<int, \Laravel\Components\Actions\Action>  $callback
     * @return $this
     */
    public function actions($callback)
    {
        $this->actionsCallback = $callback;

        return $this;
    }

    /**
     * Set the displayable name that should be used to refer to the pivot class.
     *
     * @param  string  $pivotName
     * @return $this
     */
    public function referToPivotAs($pivotName)
    {
        $this->pivotName = $pivotName;

        return $this;
    }

    /**
     * Set the displayable singular label of the resource.
     *
     * @param  string  $singularLabel
     * @return $this
     */
    public function singularLabel($singularLabel)
    {
        $this->singularLabel = $singularLabel;

        return $this;
    }

    /**
     * Return the validation key for the field.
     *
     * @return string
     */
    public function validationKey()
    {
        return $this->attribute != $this->resourceName
            ? $this->resourceName
            : $this->attribute;
    }

    /**
     * Make the field filter.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return \Laravel\Components\Fields\Filters\Filter|null
     */
    protected function makeFilter(NovaRequest $request)
    {
        if ($request->viaRelationship()
            && ($request->relationshipType ?? null) === 'belongsToMany'
            && $this->resourceClass::uriKey() === $request->viaResource
        ) {
            return null;
        }

        return new EloquentFilter($this);
    }

    /**
     * Define filterable attribute.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return string
     */
    protected function filterableAttribute(NovaRequest $request)
    {
        if ($request->viaRelationship()) {
            return $request->model()->getQualifiedKeyName();
        } else {
            return $this->resourceClass::newModel()->getQualifiedKeyName();
        }
    }

    /**
     * Define the default filterable callback.
     *
     * @return callable(\Laravel\Components\Http\Requests\NovaRequest, \Illuminate\Database\Eloquent\Builder, mixed, string):void
     */
    protected function defaultFilterableCallback()
    {
        return function (NovaRequest $request, $query, $value, $attribute) {
            $viaRelationship = $request->viaRelationship() && $request->relationshipType === 'belongsToMany';

            $query->when($viaRelationship, function ($query) use ($value) {
                $query->whereKey($value);
            }, function ($query) use ($request, $attribute, $value) {
                if ($this->resourceClass::uriKey() !== $request->viaResource) {
                    $query->whereHas($this->manyToManyRelationship, function ($query) use ($value) {
                        $query->whereKey($value);
                    });
                } else {
                    $query->whereRelation($this->manyToManyRelationship, $attribute, '=', $value);
                }
            });
        };
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array
     */
    public function serializeForFilter()
    {
        return transform($this->jsonSerialize(), function ($field) {
            return [
                'debounce' => $field['debounce'],
                'displaysWithTrashed' => false,
                'label' => $this->resourceClass::label(),
                'resourceName' => $field['resourceName'],
                'searchable' => $field['searchable'],
                'withSubtitles' => $field['withSubtitles'],
                'uniqueKey' => $field['uniqueKey'],
            ];
        });
    }

    /**
     * Make current field behaves as panel.
     *
     * @return \Laravel\Components\Panel
     */
    public function asPanel()
    {
        return Panel::make($this->name, [$this])
                    ->withMeta([
                        'prefixComponent' => true,
                    ])->withComponent('relationship-panel');
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return with(app(NovaRequest::class), function ($request) {
            return array_merge([
                'collapsable' => $this->collapsable,
                'collapsedByDefault' => $this->collapsedByDefault,
                'belongsToManyRelationship' => $this->manyToManyRelationship,
                'relationshipType' => $this->relationshipType(),
                'debounce' => $this->debounce,
                'relatable' => true,
                'perPage' => $this->resourceClass::$perPageViaRelationship,
                'validationKey' => $this->validationKey(),
                'resourceName' => $this->resourceName,
                'searchable' => $this->searchable,
                'withSubtitles' => $this->withSubtitles,
                'singularLabel' => $this->singularLabel ?? $this->resourceClass::singularLabel(),
                'showCreateRelationButton' => $this->createRelationShouldBeShown($request),
            ], parent::jsonSerialize());
        });
    }
}
