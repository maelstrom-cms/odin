<?php

namespace App\Jobs;

use App\Checkers\Uptime;
use App\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UptimeCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Website
     */
    private $website;

    /**
     * Create a new job instance.
     *
     * @param Website $website
     */
    public function __construct(Website $website)
    {
        $this->website = $website;
        $this->onQueue('default_long');
        $this->website->queue('uptime');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $checker = new Uptime($this->website);
        $checker->run();

        // The unqueue is annoyingly configured in the CacheUptimeReport class
    }

    public function tags()
    {
        return [
            static::class,
            'Website:' . $this->website->id,
        ];
    }
}
