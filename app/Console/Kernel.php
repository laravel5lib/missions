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
        Commands\SendEmails::class,
        Commands\SendWelcomeEmails::class,
        Commands\SendReservationConfirmationEmail::class,
        Commands\SendReceiptEmail::class,
        Commands\HandleLatePayments::class,
        Commands\TransferData::class,
        Commands\AddRoleToTrips::class,
        Commands\AddTodoToTrips::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('payments:penalize')
                  ->daily()
                  ->timezone('America/Detroit')
                  ->withoutOverlapping();
    }
}
