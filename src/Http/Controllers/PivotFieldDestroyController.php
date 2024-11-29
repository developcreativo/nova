<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\DeleteField;
use Laravel\Components\Http\Requests\PivotFieldDestroyRequest;
use Laravel\Components\Nova;

class PivotFieldDestroyController extends Controller
{
    /**
     * Delete the file at the given field.
     *
     * @param  \Laravel\Components\Http\Requests\PivotFieldDestroyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(PivotFieldDestroyRequest $request)
    {
        $request->authorizeForAttachment();

        DeleteField::forRequest(
            $request, $request->findFieldOrFail(),
            $pivot = $request->findPivotModel()
        )->save();

        Nova::usingActionEvent(function ($actionEvent) use ($request, $pivot) {
            $actionEvent->forAttachedResourceUpdate(
                $request, $request->findModelOrFail(), $pivot
            )->save();
        });

        return response()->noContent(200);
    }
}
