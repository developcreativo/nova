<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\RestoreResourceRequest;
use Laravel\Components\Nova;

class ResourceRestoreController extends Controller
{
    /**
     * Restore the given resource(s).
     *
     * @param  \Laravel\Components\Http\Requests\RestoreResourceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RestoreResourceRequest $request)
    {
        $request->chunks(150, function ($models) use ($request) {
            $models->each(function ($model) use ($request) {
                $model->restore();

                $request->resource()::afterRestore($request, $model);

                Nova::usingActionEvent(function ($actionEvent) use ($model, $request) {
                    $actionEvent->insert(
                        $actionEvent->forResourceRestore(Nova::user($request), collect([$model]))
                            ->map->getAttributes()->all()
                    );
                });
            });
        });

        return response()->noContent(200);
    }
}
