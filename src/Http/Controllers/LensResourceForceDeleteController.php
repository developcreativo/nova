<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Actions\Actionable;
use Laravel\Components\Http\Requests\ForceDeleteLensResourceRequest;
use Laravel\Components\Nova;

class LensResourceForceDeleteController extends Controller
{
    use DeletesFields;

    /**
     * Force delete the given resource(s).
     *
     * @param  \Laravel\Components\Http\Requests\ForceDeleteLensResourceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ForceDeleteLensResourceRequest $request)
    {
        $request->chunks(150, function ($models) use ($request) {
            $models->each(function ($model) use ($request) {
                $this->forceDeleteFields($request, $model);

                if (in_array(Actionable::class, class_uses_recursive($model))) {
                    $model->actions()->delete();
                }

                $model->forceDelete();

                Nova::usingActionEvent(function ($actionEvent) use ($model, $request) {
                    $actionEvent->insert(
                        $actionEvent->forResourceDelete(Nova::user($request), collect([$model]))
                            ->map->getAttributes()->all()
                    );
                });
            });
        });

        return response()->noContent(200);
    }
}
