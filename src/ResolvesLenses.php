<?php

namespace Laravel\Components;

use Laravel\Components\Http\Requests\NovaRequest;

trait ResolvesLenses
{
    /**
     * Get the lenses that are available for the given request.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Support\Collection<int, \Laravel\Components\Lenses\Lens>
     */
    public function availableLenses(NovaRequest $request)
    {
        return $this->resolveLenses($request)->filter->authorizedToSee($request)->values();
    }

    /**
     * Get the lenses for the given request.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Support\Collection<int, \Laravel\Components\Lenses\Lens>
     */
    public function resolveLenses(NovaRequest $request)
    {
        return collect(array_values($this->filter($this->lenses($request))));
    }

    /**
     * Get the lenses available on the resource.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }
}
