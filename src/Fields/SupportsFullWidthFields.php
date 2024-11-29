<?php

namespace Laravel\Components\Fields;

trait SupportsFullWidthFields
{
    /**
     * Indicates whether the field should use all available white-space.
     *
     * @var bool
     */
    public $fullWidth = false;

    /**
     * Set the field to use all the available white-space.
     *
     * @return $this
     */
    public function fullWidth()
    {
        $this->fullWidth = true;

        return $this;
    }
}
