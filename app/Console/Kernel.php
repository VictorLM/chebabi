<?php

namespace Intranet\Console;

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
        '\Intranet\Console\Commands\AtualizaTokenLegalOne',
        '\Intranet\Console\Commands\AtualizaTokenMSGraph',
        '\Intranet\Console\Commands\AtualizaTiposAndamentosLegalOne',
        '\Intranet\Console\Commands\AndamentosDataCloudProgressCrawler',
        '\Intranet\Console\Commands\AtualizaNoticiasAASP',
        '\Intranet\Console\Commands\EmailAniversario',
        '\Intranet\Console\Commands\BackupDB',
        '\Intranet\Console\Commands\EmailAniversarioTodos',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('AtualizaTokenLegalOne:refresh')
                ->everyFifteenMinutes()
                ->appendOutputTo(storage_path('logs/schedule.log'));
        
        $schedule->command('AtualizaTokenMSGraph:refresh')
                ->everyThirtyMinutes()
                ->appendOutputTo(storage_path('logs/schedule.log'));
        
        $schedule->command('AtualizaTiposAndamentosLegalOne:refresh')
                ->hourly()
                ->appendOutputTo(storage_path('logs/schedule.log'));
        //ANDAMENTOS DATA CLOUD
        $schedule->command('AndamentosDataCloudProgressCrawler:refresh')
                ->dailyAt('01:00')
                ->appendOutputTo(storage_path('logs/schedule.log'));

        $schedule->command('AndamentosDataCloudProgressCrawler:refresh')
                ->dailyAt('06:00')
                ->appendOutputTo(storage_path('logs/schedule.log'));

        $schedule->command('AndamentosDataCloudProgressCrawler:refresh')
                ->dailyAt('12:00')
                ->appendOutputTo(storage_path('logs/schedule.log'));

        $schedule->command('AndamentosDataCloudProgressCrawler:refresh')
                ->dailyAt('21:00')
                ->appendOutputTo(storage_path('logs/schedule.log'));
        //FIM ANDAMENTOS DATA CLOUD
        $schedule->command('AtualizaNoticiasAASP:refresh')
                ->hourly()
                ->appendOutputTo(storage_path('logs/schedule.log'));
        $schedule->command('EmailAniversario:enviar')
                ->dailyAt('09:00')
                ->appendOutputTo(storage_path('logs/schedule.log'));
        $schedule->command('db:backup')
                ->dailyAt('22:00')
                ->appendOutputTo(storage_path('logs/schedule.log'));
        $schedule->command('EmailAniversarioTodos:enviar')
                ->dailyAt('09:00')
                ->appendOutputTo(storage_path('logs/schedule.log'));
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
