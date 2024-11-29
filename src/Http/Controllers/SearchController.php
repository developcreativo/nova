<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\GlobalSearch;
use Laravel\Components\Http\Requests\GlobalSearchRequest;
use Laravel\Components\Nova;

class SearchController extends Controller
{
    /**
     * Get the global search results for the given query.
     *
     * @param  \Laravel\Components\Http\Requests\GlobalSearchRequest  $request
     * @return array
     */
    public function __invoke(GlobalSearchRequest $request)
    {
        return (new GlobalSearch(
            $request, Nova::globallySearchableResources($request)
        ))->get();
    }
}
