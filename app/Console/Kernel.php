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
    ];

    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        // Send the daily roll up every day at 2350 local time.
        $schedule->command('report:dailybyproject')->dailyAt('23:50')->timezone('America/Denver');

        // Send the weekly roll up every week on Sunday at 2345 local time.
        $schedule->command('report:weeklybyproject')->sundays()->at('23:45')->timezone('America/Denver');
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
