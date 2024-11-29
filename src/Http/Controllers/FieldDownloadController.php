<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\NovaRequest;

class FieldDownloadController extends Controller
{
    /**
     * Download the given field's contents.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(NovaRequest $request)
    {
        $resource = $request->findResourceOrFail();

        $resource->authorizeToView($request);

        return $resource->downloadableFields($request)
                    ->findFieldByAttribute($request->field, function () {
                        abort(404);
                    })
                    ->toDownloadResponse($request, $resource);
    }
}
