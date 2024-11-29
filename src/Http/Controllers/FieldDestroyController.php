<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Contracts\Downloadable;
use Laravel\Components\DeleteField;
use Laravel\Components\Http\Requests\NovaRequest;
use Laravel\Components\Nova;

class FieldDestroyController extends Controller
{
    /**
     * Delete the file at the given field.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(NovaRequest $request)
    {
        $resource = $request->findResourceOrFail();

        $resource->authorizeToUpdate($request);

        $field = $resource->updateFields($request)
                    ->whereInstanceOf(Downloadable::class)
                    ->findFieldByAttribute($request->field, function () {
                        abort(404);
                    });

        DeleteField::forRequest(
            $request, $field, $resource->resource
        )->save();

        Nova::usingActionEvent(function ($actionEvent) use ($request, $resource) {
            $actionEvent->forResourceUpdate(
                Nova::user($request), $resource->resource
            )->save();
        });

        return response()->noContent(200);
    }
}
