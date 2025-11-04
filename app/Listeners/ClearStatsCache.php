<?php

namespace App\Listeners;

use App\Events\NewHitEvent;
use Illuminate\Support\Facades\Cache;

class ClearStatsCache
{
    /**
     * Handle the event.
     */
    public function handle(NewHitEvent $event): void
    {
        Cache::forget('cached_stat_' . $event->slug);
    }
}
