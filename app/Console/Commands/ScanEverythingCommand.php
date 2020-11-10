<?php

namespace App\Console\Commands;

use App\Website;
use App\Jobs\DnsCheck;
use App\Jobs\PageCheck;
use App\Jobs\RobotsCheck;
use App\Jobs\UptimeCheck;
use App\Checkers\Certificate;
use App\Jobs\CertificateCheck;
use Illuminate\Console\Command;

class ScanEverythingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:everything';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs every checker for every website.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Website::all()->each(function (Website $website) {
            dump('Running monitors for ' . $website->url);
            CertificateCheck::dispatch($website);
            dump('Certificate check queued.');
            RobotsCheck::dispatch($website);
            dump('Robots check queued.');
            UptimeCheck::dispatch($website);
            dump('Uptime check queued.');
            DnsCheck::dispatch($website);
            dump('DNS check queued.');
            PageCheck::dispatch($website);
            dump('DNS check queued.');
            echo PHP_EOL;
        });
    }
}
