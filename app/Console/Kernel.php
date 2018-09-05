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
        Commands\SendEmails::class, //Commands/Emails/Send
        Commands\SendWelcomeEmails::class, //Emails/Users/SendWelcome
        Commands\SendReservationConfirmationEmail::class, //Commands/Emails/Reservations/SendConfirmation
        Commands\SendReceiptEmail::class, //Commands/Email/SendReceipt
        Commands\HandleLatePayments::class, //Commands/Payments/Penalize
        Commands\UsePromoCode::class, //Commands/PromoCodes/UseCode
        Commands\AddRoleToTrips::class, //Commands/Roles/Add
        Commands\AddTodoToTrips::class, //Commands/Tasks/Add
        Commands\ArchiveOldFunds::class, //Commands/Funds/Archive
        Commands\Utilities\UpdateRoomingPlansToMultiGroups::class,
        Commands\Utilities\ExportReservationProfilePics::class,
        \Bugsnag\BugsnagLaravel\Commands\DeployCommand::class,
        Commands\RunScenario::class,
        Commands\SendReminders::class,
        Commands\TransferFundraiserMedia::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('payments:penalize --force')
                 ->daily()
                 ->timezone('America/Detroit')
                 ->withoutOverlapping();

        $schedule->command('reminders:send --force')->daily();
    }
    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
