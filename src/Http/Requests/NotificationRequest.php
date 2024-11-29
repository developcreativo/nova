<?php

namespace Laravel\Components\Http\Requests;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Laravel\Components\Http\Resources\NotificationResource;
use Laravel\Components\Notifications\Notification;
use Laravel\Components\Nova;

class NotificationRequest extends NovaRequest
{
    /**
     * @return AnonymousResourceCollection
     */
    public function notifications()
    {
        return NotificationResource::collection(
            Notification::whereNotifiableId(Nova::user($this)->getKey())
                ->latest()
                ->take(100)
                ->get()
        );
    }

    public function unreadCount(): int
    {
        return Notification::unread()->whereNotifiableId(
            Nova::user($this)->getKey()
        )->count();
    }
}
