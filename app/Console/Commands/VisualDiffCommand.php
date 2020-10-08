<?php

namespace App\Console\Commands;

use App\Website;
use App\Jobs\VisualDiffCheck;
use Illuminate\Console\Command;

class VisualDiffCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:visual-diff {website} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will run the visual diff check for a single website.';

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
     */
    public function handle()
    {
        $forced = $this->option('force', false);
        $websiteId = $this->argument('website');
        $website = Website::findOrFail($websiteId);

        if (!$website->visual_diff_enabled && !$forced) {
            return $this->error('Visual diffs disabled for ' . $website->url . ', use --force to force a check.');
        }

        $website->visual_urls_to_scan->each(function ($url) use ($website) {
            dump('Visual diff running for ' . $url);
            VisualDiffCheck::dispatchNow($website, $url);
        });
    }
}
