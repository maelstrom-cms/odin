<?php

namespace App\Jobs;

use App\Website;
use App\Checkers\Page;
use App\Checkers\Certificate;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PageCheck implements ShouldQueue
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
        $this->website->queue('crawler');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $checker = new Page($this->website);
        $checker->run();

        $this->website->unqueue('crawler');
    }

    public function tags()
    {
        return [
            static::class,
            'Website:' . $this->website->id,
        ];
    }
}
