<?php

namespace Laravel\Components\Fields;

use Laravel\Components\Contracts\ListableField;
use Laravel\Components\Contracts\RelatableField;
use Laravel\Components\Panel;

/**
 * @method static static make(mixed $name, string|null $attribute = null, string|null $resource = null)
 */
class HasManyThrough extends HasMany implements ListableField, RelatableField
{
    use Collapsable;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'has-many-through-field';

    /**
     * The name of the Eloquent "has many through" relationship.
     *
     * @var string
     */
    public $hasManyThroughRelationship;

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
        parent::__construct($name, $attribute, $resource);

        $this->hasManyThroughRelationship = $this->attribute = $attribute ?? ResourceRelationshipGuesser::guessRelation($name);
    }

    /**
     * Get the relationship name.
     *
     * @return string
     */
    public function relationshipName()
    {
        return $this->hasManyThroughRelationship;
    }

    /**
     * Get the relationship type.
     *
     * @return string
     */
    public function relationshipType()
    {
        return 'hasManyThrough';
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
        return array_merge([
            'collapsable' => $this->collapsable,
            'collapsedByDefault' => $this->collapsedByDefault,
            'hasManyThroughRelationship' => $this->hasManyThroughRelationship,
            'relatable' => true,
            'perPage' => $this->resourceClass::$perPageViaRelationship,
            'resourceName' => $this->resourceName,
            'singularLabel' => $this->singularLabel ?? $this->resourceClass::singularLabel(),
        ], parent::jsonSerialize());
    }
}
