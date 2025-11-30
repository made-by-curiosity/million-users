<?php

namespace App\Listeners;

use App\Events\UserUpdated;
use Illuminate\Support\Facades\Log;

class LogUserUpdated
{
    public function handle(UserUpdated $event): void
    {
        Log::info('User updated: ' . $event->user->id);
    }
}
