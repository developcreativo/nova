<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Actions\Actionable;
use Laravel\Components\Http\Requests\DeleteLensResourceRequest;
use Laravel\Components\Nova;

class LensResourceDestroyController extends Controller
{
    use DeletesFields;

    /**
     * Destroy the given resource(s).
     *
     * @param  \Laravel\Components\Http\Requests\DeleteLensResourceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(DeleteLensResourceRequest $request)
    {
        $request->chunks(150, function ($models) use ($request) {
            $models->each(function ($model) use ($request) {
                $this->deleteFields($request, $model);

                if (in_array(Actionable::class, class_uses_recursive($model))) {
                    $model->actions()->delete();
                }

                $model->delete();

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
