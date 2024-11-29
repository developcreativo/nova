<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\LensCardRequest;

class LensCardController extends Controller
{
    /**
     * List the cards for the given lens.
     *
     * @param  \Laravel\Components\Http\Requests\LensCardRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(LensCardRequest $request)
    {
        return response()->json(
            $request->availableCards()
        );
    }
}
