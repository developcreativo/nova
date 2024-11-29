<?php

namespace Laravel\Components\Fields;

class MorphOne extends HasOne
{
    /**
     * Get the relationship type.
     *
     * @return string
     */
    public function relationshipType()
    {
        return 'morphOne';
    }
}
