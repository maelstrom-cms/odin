<?php

namespace App\Console\Commands;

use App\Website;
use App\Jobs\CertificateCheck;
use Illuminate\Console\Command;

class CertificateCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:ssl {website}';

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
        $websiteId = $this->argument('website');

        CertificateCheck::dispatchNow(
            Website::findOrFail($websiteId)
        );
    }
}
