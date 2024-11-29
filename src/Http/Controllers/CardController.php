<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\CardRequest;

class CardController extends Controller
{
    /**
     * List the cards for the given resource.
     *
     * @param  \Laravel\Components\Http\Requests\CardRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(CardRequest $request)
    {
        return response()->json(
            $request->availableCards()
        );
    }
}
