<?php

namespace App\Console\Commands;

use App\Jobs\RobotsCheck;
use App\Website;
use Illuminate\Console\Command;

class RobotCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:robots {website}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks the robots.txt for a website.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $websiteId = $this->argument('website');

        RobotsCheck::dispatchNow(
            Website::findOrFail($websiteId)
        );
    }
}
