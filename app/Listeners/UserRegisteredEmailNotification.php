<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Jobs\UserRegisteredEmailJob;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserRegisteredEmailNotification implements ShouldQueue
{
    /**
     * @param UserRegistered $event
     * @return void
     */
    public function handle(UserRegistered $event): void
    {
        dispatch(new UserRegisteredEmailJob($event->user));
    }
}
