<?php

namespace App\Jobs;

use App\Events\NewHitEvent;
use App\Models\Link;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LogLinkHitJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Link $link, protected string $ip, protected string $userAgent)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->link->linkHits()->create([
            'ip' => $this->ip,
            'user_agent' => $this->userAgent,
        ]);

        event(new NewHitEvent($this->link->slug));
    }
}
