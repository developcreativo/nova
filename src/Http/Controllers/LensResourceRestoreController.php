<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\RestoreLensResourceRequest;
use Laravel\Components\Nova;

class LensResourceRestoreController extends Controller
{
    /**
     * Force delete the given resource(s).
     *
     * @param  \Laravel\Components\Http\Requests\RestoreLensResourceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RestoreLensResourceRequest $request)
    {
        $request->chunks(150, function ($models) use ($request) {
            $models->each(function ($model) use ($request) {
                $model->restore();

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
