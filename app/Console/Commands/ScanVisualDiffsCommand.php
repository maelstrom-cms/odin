<?php

namespace App\Console\Commands;

use App\Jobs\VisualDiffCheck;
use App\Jobs\CertificateCheck;
use App\Jobs\UptimeCheck;
use App\Website;
use Illuminate\Console\Command;

class ScanVisualDiffsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:visual-diffs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the visual diffs for all enabled websites.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Website::canScanVisualDiffs()->get()->each(function (Website $website) {
            $website->visual_urls_to_scan->each(function ($url) use ($website) {
                dump('Visual diff check queued for ' . $url);
                VisualDiffCheck::dispatch($website, $url);
            });
        });
    }
}
