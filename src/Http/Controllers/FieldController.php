<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Contracts\RelatableField;
use Laravel\Components\Http\Requests\NovaRequest;

class FieldController extends Controller
{
    /**
     * Retrieve the given field for the given resource.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(NovaRequest $request)
    {
        $resource = $request->newResource();

        $fields = $request->relatable
                        ? $resource->availableFieldsOnIndexOrDetail($request)->whereInstanceOf(RelatableField::class)
                        : $resource->availableFields($request);

        return response()->json(
            $fields->findFieldByAttribute($request->field, function () {
                abort(404);
            })
        );
    }
}
