<?php

namespace Laravel\Components\Contracts;

/**
 * @mixin \Laravel\Components\Fields\Field
 *
 * @property string $attribute
 * @property \Laravel\Components\Resource $resourceClass
 * @property string $resourceName
 */
interface RelatableField
{
    /**
     * Get the relationship name.
     *
     * @return string
     */
    public function relationshipName();

    /**
     * Get the relationship type.
     *
     * @return string
     */
    public function relationshipType();
}
