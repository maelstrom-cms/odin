<?php

namespace App\Jobs;

use App\Website;
use App\Checkers\Robots;
use App\Checkers\VisualDiff;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class VisualDiffCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Website
     */
    private $website;

    private $url;

    /**
     * Create a new job instance.
     *
     * @param Website $website
     * @param string $url
     */
    public function __construct(Website $website, string $url)
    {
        $this->website = $website;
        $this->url = $url;
        $this->onQueue('default_long');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $checker = new VisualDiff($this->website, $this->url);
        $checker->run();
    }

    public function tags()
    {
        return [
            static::class,
            'Website:' . $this->website->id,
        ];
    }
}
