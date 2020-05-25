<?php

namespace App\Jobs;

use App\Website;
use App\Checkers\OpenGraph;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class OpenGraphCheck implements ShouldQueue
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
        $this->website->queue('og');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $checker = new OpenGraph($this->website);
        $checker->run();

        $this->website->unqueue('og');
    }

    public function tags()
    {
        return [
            static::class,
            'Website:' . $this->website->id,
        ];
    }
}
