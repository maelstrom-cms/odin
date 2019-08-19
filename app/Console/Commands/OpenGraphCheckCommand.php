<?php

namespace App\Console\Commands;

use App\Jobs\OpenGraphCheck;
use App\Jobs\RobotsCheck;
use App\Website;
use Illuminate\Console\Command;

class OpenGraphCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:opengraph {website}';

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

        OpenGraphCheck::dispatchNow(
            Website::findOrFail($websiteId)
        );
    }
}
