<?php

namespace App\Console\Commands;

use App\Website;
use App\Jobs\PageCheck;
use Illuminate\Console\Command;

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
        Website::canCrawl()->get()->each(function (Website $website) {
            PageCheck::dispatch($website);
        });
    }
}
