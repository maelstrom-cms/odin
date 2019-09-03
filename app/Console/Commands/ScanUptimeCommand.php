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
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Website::where('uptime_enabled', 1)->get()->each(function (Website $website) {
            UptimeCheck::dispatch($website);
            dump('Uptime check queued for ' . $website->url);
        });
    }
}
