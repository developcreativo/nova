<?php

namespace Laravel\Components;

use Laravel\Components\Http\Requests\NovaRequest;

trait ResolvesCards
{
    /**
     * Get the cards that are available for the given request.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Support\Collection<int, \Laravel\Components\Metrics\Metric|\Laravel\Components\Card>
     */
    public function availableCards(NovaRequest $request)
    {
        return $this->resolveCards($request)
            ->reject(function ($card) {
                return $card->onlyOnDetail;
            })
        ->filter->authorize($request)->values();
    }

    /**
     * Get the cards that are available for the given request.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Support\Collection<int, \Laravel\Components\Metrics\Metric|\Laravel\Components\Card>
     */
    public function availableCardsForDetail(NovaRequest $request)
    {
        return $this->resolveCards($request)
            ->filter(function ($card) {
                return $card->onlyOnDetail;
            })
            ->filter->authorize($request)->values();
    }

    /**
     * Get the cards for the given request.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Support\Collection<int, \Laravel\Components\Metrics\Metric|\Laravel\Components\Card>
     */
    public function resolveCards(NovaRequest $request)
    {
        return collect(array_values($this->filter($this->cards($request))));
    }

    /**
     * Get the cards available on the entity.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }
}
