<?php

namespace App\Console\Commands;

use App\Jobs\UptimeCheck;
use App\Website;
use Illuminate\Console\Command;
use App\Jobs\CacheUptimeReport;

class UptimeCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:uptime {website}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds an uptime checkpoint for a single website.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $websiteId = $this->argument('website');
        $website = Website::findOrFail($websiteId);

        UptimeCheck::dispatchNow($website);
        CacheUptimeReport::dispatchNow($website);
    }
}
