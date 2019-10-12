<?php

namespace App\Console\Commands;

use App\Jobs\CertificateCheck;
use App\Jobs\DnsCheck;
use App\Jobs\RobotsCheck;
use App\Jobs\UptimeCheck;
use App\Website;
use Illuminate\Console\Command;

class AllCheckersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:all {website}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs all checks for an individual website.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $websiteId = $this->argument('website');
        $website = Website::findOrFail($websiteId);

        DnsCheck::dispatchNow($website);
        RobotsCheck::dispatchNow($website);
        UptimeCheck::dispatchNow($website);
        CertificateCheck::dispatchNow($website);
    }
}
