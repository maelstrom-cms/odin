<?php

namespace App\Console\Commands;

use App\Jobs\CertificateCheck;
use App\Jobs\RobotsCheck;
use App\Website;
use Illuminate\Console\Command;

class ScanRobotsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:robots';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the robots.txt checks for all enabled websites.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Website::canScanRobots()->get()->each(function (Website $website) {
            RobotsCheck::dispatch($website);
            dump('Robots check queued for ' . $website->url);
        });
    }
}
