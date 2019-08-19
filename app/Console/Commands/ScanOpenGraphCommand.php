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
        Website::all()->each(function (Website $website) {
            OpenGraphCheck::dispatch($website);
            dump('Open Graph check queued for ' . $website->url);
        });
    }
}
