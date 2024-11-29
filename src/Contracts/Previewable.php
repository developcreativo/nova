<?php

namespace Laravel\Components\Contracts;

/**
 * @mixin \Laravel\Components\Fields\Field
 */
interface Previewable
{
    /**
     * Return a preview for the given field value.
     *
     * @param  string  $value
     * @return mixed
     */
    public function previewFor($value);
}
