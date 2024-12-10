<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Fazer backup diário às 23:00
        $schedule->command('db:backup')->dailyAt('23:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    protected $commands = [
        \App\Console\Commands\CheckProductImages::class,
        \App\Console\Commands\FixProductImages::class,
        \App\Console\Commands\DeepCheckProductImages::class,
        \App\Console\Commands\RecoverProductImages::class,
        \App\Console\Commands\ResetDatabase::class,
    ];
}
