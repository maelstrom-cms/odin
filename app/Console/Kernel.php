<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    protected function scheduleTimezone()
    {
        return 'Europe/London';
    }

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('scan:uptime')->everyFiveMinutes()->withoutOverlapping()->runInBackground();
        $schedule->command('scan:robots')->hourly()->withoutOverlapping()->runInBackground();
        $schedule->command('scan:dns')->hourly()->withoutOverlapping()->runInBackground();
        $schedule->command('scan:certificates')->dailyAt('08:00:00')->withoutOverlapping()->runInBackground();
        $schedule->command('scan:opengraph')->dailyAt('08:00:00')->withoutOverlapping()->runInBackground();
        $schedule->command('scan:consoles')->daily()->withoutOverlapping();
        $schedule->command('horizon:snapshot')->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
