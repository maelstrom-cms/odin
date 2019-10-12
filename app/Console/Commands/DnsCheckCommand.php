<?php

namespace App\Console\Commands;

use App\Website;
use App\Jobs\DnsCheck;
use Illuminate\Console\Command;

class DnsCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:dns {website}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks the DNS entries for a specific website';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $websiteId = $this->argument('website');

        DnsCheck::dispatchNow(
            Website::findOrFail($websiteId)
        );
    }
}
