<?php

namespace App\Console\Commands;

use App\CrawledPage;
use Illuminate\Console\Command;
use App\Jobs\BrowserConsoleCheck;

class BrowserConsoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:console {crawled_page}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check browser console for errors for a specific web page.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $pageId = $this->argument('crawled_page');

        $page = CrawledPage::findOrFail($pageId);
        $website = $page->website;

        BrowserConsoleCheck::dispatchNow(
            $website, $page
        );
    }
}
