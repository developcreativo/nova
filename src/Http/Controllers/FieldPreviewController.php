<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Contracts\Previewable;
use Laravel\Components\Http\Requests\NovaRequest;

class FieldPreviewController extends Controller
{
    /**
     * Delete the file at the given field.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(NovaRequest $request)
    {
        $request->validate(['value' => ['nullable', 'string']]);

        $resource = $request->newResource();

        /** @var \Laravel\Components\Fields\Field&\Laravel\Components\Contracts\Previewable $field */
        $field = $resource->availableFields($request)
                    ->whereInstanceOf(Previewable::class)
                    ->findFieldByAttribute($request->field, function () {
                        abort(404);
                    });

        return response()->json([
            'preview' => $field->previewFor($request->value),
        ]);
    }
}
