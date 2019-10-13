<?php

namespace App\Console\Commands;

use App\Website;
use App\Jobs\PageCheck;
use Illuminate\Console\Command;

class CrawlSiteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:pages {website}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawls a website and collects all the linked URLs';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $websiteId = $this->argument('website');

        PageCheck::dispatchNow(
            Website::findOrFail($websiteId)
        );
    }
}
