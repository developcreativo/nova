<?php

namespace Laravel\Components\Actions;

use Illuminate\Support\Str;

class ActionMethod
{
    /**
     * Determine the appropriate "handle" method for the given models.
     *
     * @param  \Laravel\Components\Actions\Action  $action
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return string
     */
    public static function determine(Action $action, $model)
    {
        if (! is_null($action->handleCallback)) {
            return 'handleUsingCallback';
        }

        $method = 'handleFor'.Str::plural(class_basename($model));

        if (method_exists($action, $method)) {
            return $method;
        }

        return 'handle';
    }
}
