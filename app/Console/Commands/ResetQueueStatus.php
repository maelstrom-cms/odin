<?php

namespace App\Console\Commands;

use App\Website;
use Illuminate\Console\Command;
use Morrislaptop\LaravelQueueClear\Contracts\Clearer as ClearerContract;

class ResetQueueStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resets the queue status within the database';

    /**
     * @var ClearerContract
     */
    private $clearer;

    /**
     * Create a new command instance.
     *
     * @param ClearerContract $clearer
     */
    public function __construct(ClearerContract $clearer)
    {
        $this->clearer = $clearer;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Website::query()->update([
            'in_queue_og' => 0,
            'in_queue_robots' => 0,
            'in_queue_ssl' => 0,
            'in_queue_crawler' => 0,
            'in_queue_dns' => 0,
            'in_queue_uptime' => 0,
        ]);

        $cleared = $this->clearer->clear('redis', 'default');
        $this->info(sprintf('Cleared %d jobs on "default"', $cleared));

        $cleared = $this->clearer->clear('redis', 'default_long');
        $this->info(sprintf('Cleared %d jobs on "default_long"', $cleared));

        $this->info('Queue statuses all reset!');
        $this->call('queue:flush');
    }
}
