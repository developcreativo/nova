<?php

namespace Laravel\Components\Http\Controllers\Pages;

use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Laravel\Components\Http\Requests\DashboardRequest;
use Laravel\Components\Http\Resources\DashboardViewResource;
use Laravel\Components\Menu\Breadcrumb;
use Laravel\Components\Menu\Breadcrumbs;
use Laravel\Components\Nova;

class DashboardController extends Controller
{
    /**
     * Show Resource Create page using Inertia.
     *
     * @param  \Laravel\Components\Http\Requests\DashboardRequest  $request
     * @param  string  $name
     * @return \Illuminate\Http\RedirectResponse|\Inertia\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function __invoke(DashboardRequest $request, $name = 'main')
    {
        DashboardViewResource::make($name)->authorizedDashboardForRequest($request);

        return Inertia::render('Nova.Dashboard', [
            'breadcrumbs' => $this->breadcrumbs($request, $name),
            'name' => $name,
        ]);
    }

    /**
     * Get breadcrumb menu for the page.
     *
     * @param  \Laravel\Components\Http\Requests\DashboardRequest  $request
     * @param  string  $name
     * @return \Laravel\Components\Menu\Breadcrumbs
     */
    protected function breadcrumbs(DashboardRequest $request, string $name)
    {
        return Breadcrumbs::make([
            Breadcrumb::make(Nova::__('Dashboards')),
            Breadcrumb::make(Nova::dashboardForKey($name, $request)->label()),
        ]);
    }
}
