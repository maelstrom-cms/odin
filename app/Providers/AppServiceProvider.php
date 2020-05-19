<?php

namespace App\Providers;

use App\Website;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//        Website::observe(WebsiteObserver::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Queue::failing(function (JobFailed $event) {
            logger()->error('job failed', [$event]);
            // $event->connectionName
            // $event->job
            // $event->exception
        });
    }
}
