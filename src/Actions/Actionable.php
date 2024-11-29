<?php

namespace Laravel\Components\Actions;

use Laravel\Components\Nova;

trait Actionable
{
    /**
     * Get all of the action events for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function actions()
    {
        return $this->morphMany(Nova::actionEvent(), 'actionable');
    }
}
