<?php

namespace Modules\User\Listeners;


use Illuminate\Support\Facades\Log;
use Modules\User\Events\UserCreated;
use Modules\User\Events\UserDeleted;
use Modules\User\Events\UserUpdated;

class UserEventListener
{
    /**
     * @param $event
     */
    public function onCreated(UserCreated $event)
    {
        Log::notice(__('logs.backend.users.created', ['user' => $event->user->id]));
    }

    /**
     * @param $event
     */
    public function onUpdated(UserUpdated $event)
    {
        Log::notice(__('logs.backend.users.updated', ['user' => $event->user->id]));
    }

    /**
     * @param $event
     */
    public function onDeleted(UserDeleted $event)
    {
        Log::notice(__('logs.backend.users.deleted', ['user' => $event->user->id]));
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            UserCreated::class,
            'Modules\User\Listeners\UserEventListener@onCreated'
        );

        $events->listen(
            UserUpdated::class,
            'Modules\User\Listeners\UserEventListener@onUpdated'
        );

        $events->listen(
            UserDeleted::class,
            'Modules\User\Listeners\UserEventListener@onDeleted'
        );
    }
}
