<?php

namespace App\Jobs;

use App\Website;
use App\Checkers\Certificate;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CertificateCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Website
     */
    private $website;

    /**
     * Create a new job instance.
     *
     * @param Website $website
     */
    public function __construct(Website $website)
    {
        $this->website = $website;
        $this->website->queue('ssl');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $checker = new Certificate($this->website);
        $checker->run();
        $this->website->unqueue('ssl');
    }

    public function tags()
    {
        return [
            static::class,
            'Website:' . $this->website->id,
        ];
    }
}
