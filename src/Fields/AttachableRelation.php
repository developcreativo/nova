<?php

namespace Laravel\Components\Fields;

use Laravel\Components\Http\Requests\NovaRequest;

trait AttachableRelation
{
    /**
     * Determines if the display values should be automatically sorted.
     *
     * @var (callable(\Laravel\Components\Http\Requests\NovaRequest):(bool))|bool
     */
    public $reordersOnAttachableCallback = true;

    /**
     * Determine if the display values should be automatically sorted when rendering attachable relation.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return bool
     */
    public function shouldReorderAttachableValues(NovaRequest $request)
    {
        if (is_callable($this->reordersOnAttachableCallback)) {
            return call_user_func($this->reordersOnAttachableCallback, $request);
        }

        return $this->reordersOnAttachableCallback;
    }

    /**
     * Determine reordering on attachables.
     *
     * @return $this
     */
    public function dontReorderAttachables()
    {
        $this->reordersOnAttachableCallback = false;

        return $this;
    }

    /**
     * Determine reordering on attachables.
     *
     * @param  (callable(\Laravel\Components\Http\Requests\NovaRequest):(bool))|bool  $value
     * @return $this
     */
    public function reorderAttachables($value = true)
    {
        $this->reordersOnAttachableCallback = $value;

        return $this;
    }
}
