<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\NotificationRequest;
use Laravel\Components\Notifications\Notification;
use Laravel\Components\Nova;

class NotificationDeleteAllController extends Controller
{
    /**
     * Delete all notifications.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(NotificationRequest $request)
    {
        Notification::whereNotifiableId(Nova::user($request)->getKey())->delete();

        return response()->json();
    }
}
