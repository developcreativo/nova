<?php

namespace Laravel\Components\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Components\Http\Requests\NotificationRequest;

class NotificationIndexController extends Controller
{
    /**
     * Return the details for the Dashboard.
     *
     * @param  \Laravel\Components\Http\Requests\NotificationRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(NotificationRequest $request)
    {
        return response()->json([
            'notifications' => $request->notifications(),
            'unread' => $request->unreadCount(),
        ]);
    }
}
