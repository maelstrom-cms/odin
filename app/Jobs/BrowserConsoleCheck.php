<?php

namespace App\Jobs;

use App\CrawledPage;
use Illuminate\Bus\Queueable;
use App\Checkers\BrowserConsole;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BrowserConsoleCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var CrawledPage
     */
    private $page;

    /**
     * Create a new job instance.
     *
     * @param CrawledPage $page
     */
    public function __construct(CrawledPage $page)
    {
        $this->page = $page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $checker = new BrowserConsole($this->page);
        $checker->run();
    }
}
