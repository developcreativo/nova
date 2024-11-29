<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Actions\Action;
use Laravel\Components\Actions\ActionCollection;
use Laravel\Components\Http\Requests\ActionRequest;
use Laravel\Components\Http\Requests\NovaRequest;
use Laravel\Components\Nova;
use Laravel\Components\Resource;

class ActionController extends Controller
{
    /**
     * List the actions for the given resource.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(NovaRequest $request)
    {
        $resourceId = with($request->input('resources'), function ($resourceIds) {
            return is_array($resourceIds) && count($resourceIds) === 1 ? $resourceIds[0] : null;
        });

        $resource = $request->newResourceWith(
            $request->findModel($resourceId) ?? $request->model()
        );

        return response()->json(with([
            'actions' => $this->availableActions($request, $resource),
            'pivotActions' => [
                'name' => Nova::humanize($request->pivotName()),
                'actions' => $resource->availablePivotActions($request),
            ],
        ], function ($payload) use ($resource, $request) {
            $actionCounts = ($request->display !== 'detail' ? $payload['actions'] : $resource->resolveActions($request))->countsByTypeOnIndex();
            $pivotActionCounts = ActionCollection::make($payload['pivotActions']['actions'])->countsByTypeOnIndex();

            $payload['counts'] = [
                'standalone' => $actionCounts['standalone'] + $pivotActionCounts['standalone'],
                'resource' => $actionCounts['resource'] + $pivotActionCounts['resource'],
            ];

            return $payload;
        }));
    }

    /**
     * Perform an action on the specified resources.
     *
     * @param  \Laravel\Components\Http\Requests\ActionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ActionRequest $request)
    {
        $request->validateFields();

        return $request->action()->handleRequest($request);
    }

    /**
     * Sync an action field on the specified resources.
     *
     * @param  \Laravel\Components\Http\Requests\ActionRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync(ActionRequest $request)
    {
        $action = $this->availableActions($request, $request->newResource())
            ->first(function ($action) use ($request) {
                return $action->uriKey() === $request->query('action');
            });

        abort_unless($action instanceof Action, 404);

        return response()->json(
            collect($action->fields($request))
                ->filter(function ($field) use ($request) {
                    return $request->query('field') === $field->attribute &&
                        $request->query('component') === $field->dependentComponentKey();
                })->each->syncDependsOn($request)
                ->first()
        );
    }

    /**
     * Get the available actions for the request.
     *
     * @param  \Laravel\Components\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Components\Resource  $resource
     * @return \Laravel\Components\Actions\ActionCollection<int, \Laravel\Components\Actions\Action>
     */
    protected function availableActions(NovaRequest $request, Resource $resource)
    {
        switch ($request->display) {
            case 'index':
                $method = 'availableActionsOnIndex';
                break;
            case 'detail':
                $method = 'availableActionsOnDetail';
                break;
            default:
                $method = 'availableActions';
        }

        return $resource->{$method}($request);
    }
}
