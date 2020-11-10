<?php

namespace App\Console\Commands;

use App\Jobs\PageCheck;
use App\Website;
use Illuminate\Console\Command;

class ScanCrawlerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:crawler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Schedules the crawler for all websites and collects all the linked URLs.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Website::canCrawl()->get()->each(function (Website $website) {
            dump('Crawler queued for ' . $website->url);
            PageCheck::dispatch($website);
        });
    }
}
