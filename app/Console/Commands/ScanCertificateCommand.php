<?php

namespace App\Console\Commands;

use App\Jobs\CertificateCheck;
use App\Jobs\DnsCheck;
use App\Jobs\RobotsCheck;
use App\Jobs\UptimeCheck;
use App\Website;
use Illuminate\Console\Command;

class ScanCertificateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:certificates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the SSL checks for all enabled websites.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Website::canScanCertificates()->get()->each(function (Website $website) {
            CertificateCheck::dispatch($website);
            dump('Certificate check queued for ' . $website->url);
        });
    }
}
