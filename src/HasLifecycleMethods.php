<?php

namespace Laravel\Components;

use Illuminate\Database\Eloquent\Model;
use Laravel\Components\Http\Requests\NovaRequest;

trait HasLifecycleMethods
{
    /**
     * Register a callback to be called after the resource is created.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public static function afterCreate(NovaRequest $request, Model $model)
    {
        //
    }

    /**
     * Register a callback to be called after the resource is updated.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public static function afterUpdate(NovaRequest $request, Model $model)
    {
        //
    }

    /**
     * Register a callback to be called after the resource is deleted.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public static function afterDelete(NovaRequest $request, Model $model)
    {
        //
    }

    /**
     * Register a callback to be called after the resource is force-deleted.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public static function afterForceDelete(NovaRequest $request, Model $model)
    {
        //
    }

    /**
     * Register a callback to be called after the resource is restored.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public static function afterRestore(NovaRequest $request, Model $model)
    {
        //
    }
}
