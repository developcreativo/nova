<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\NotificationRequest;
use Laravel\Components\Notifications\Notification;
use Laravel\Components\Nova;

class NotificationReadAllController extends Controller
{
    /**
     * Mark the given notification as read.
     *
     * @param  \Laravel\Components\Http\Requests\NotificationRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(NotificationRequest $request)
    {
        Notification::unread()->whereNotifiableId(Nova::user($request)->getKey())->update(['read_at' => now()]);

        return response()->json();
    }
}
