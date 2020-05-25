<?php

namespace App\Console\Commands;

use App\Jobs\CertificateCheck;
use App\Jobs\OpenGraphCheck;
use App\Website;
use Illuminate\Console\Command;

class ScanOpenGraphCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:opengraph';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the Open graph checks for all enabled websites.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Website::canScanOpenGraph()->get()->each(function (Website $website) {
            OpenGraphCheck::dispatch($website);
            dump('Open Graph check queued for ' . $website->url);
        });
    }
}
