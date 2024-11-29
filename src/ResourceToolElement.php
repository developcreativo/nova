<?php

namespace Laravel\Components;

use Laravel\Components\Fields\FieldElement;

class ResourceToolElement extends FieldElement
{
    /**
     * Create a new resource tool.
     *
     * @param  string  $component
     * @return void
     */
    public function __construct($component)
    {
        parent::__construct($component);

        $this->onlyOnDetail();
    }
}
