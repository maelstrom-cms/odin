<?php

namespace App\Console\Commands;

use App\Jobs\CertificateCheck;
use App\Jobs\DnsCheck;
use App\Website;
use Illuminate\Console\Command;

class ScanDnsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:dns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the DNS checks for all enabled websites.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Website::canScanDns()->get()->each(function (Website $website) {
            DnsCheck::dispatch($website);
            dump('DNS check queued for ' . $website->url);
        });
    }
}
