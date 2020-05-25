<?php

namespace App\Console\Commands;

use App\Jobs\CertificateCheck;
use App\Jobs\UptimeCheck;
use App\Website;
use Illuminate\Console\Command;

class ScanUptimeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:uptime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates an uptime checkpoint for all enabled websites.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Website::canScanUptime()->get()->each(function (Website $website) {
            UptimeCheck::dispatch($website);
            dump('Uptime check queued for ' . $website->url);
        });
    }
}
