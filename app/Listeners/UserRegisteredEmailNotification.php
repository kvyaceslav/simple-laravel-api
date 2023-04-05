<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Jobs\UserRegisteredEmailJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
