<?php

namespace Laravel\Components\Http\Controllers\Pages;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Components\Nova;

class HomeController extends Controller
{
    /**
     * Show Nova homepage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request)
    {
        return redirect(Nova::url(Nova::resolveInitialPath($request)));
    }
}
