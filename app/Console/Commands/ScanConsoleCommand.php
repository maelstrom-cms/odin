<?php

namespace App\Console\Commands;

use App\CrawledPage;
use App\Jobs\CertificateCheck;
use App\Jobs\DnsCheck;
use App\Website;
use Illuminate\Console\Command;
use App\Jobs\BrowserConsoleCheck;

class ScanConsoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:consoles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the console error checks for all enabled websites.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Website::with('crawledPages')->where('crawler_enabled', 1)->get()->each(function (Website $website) {
            $website->crawledPages->each(function (CrawledPage $page) {
                BrowserConsoleCheck::dispatch($page);
                dump('console error check queued for ' . $page->url);
            });
        });
    }
}
