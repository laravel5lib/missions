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
//        Commands\TransferData::class, // TODO delete
        Commands\UsePromoCode::class, //Commands/PromoCodes/UseCode
        Commands\AddRoleToTrips::class, //Commands/Roles/Add
        Commands\AddTodoToTrips::class, //Commands/Tasks/Add
        Commands\AddRequirementToTrips::class, //Commands/Requirements/Add
//        Commands\ExtractAccounting::class, // TODO delete
        Commands\ArchiveOldFunds::class, //Commands/Funds/Archive
        Commands\Requirements\Add::class,
        Commands\Requirements\Remove::class,
        Commands\Utilities\UpdateRoomingPlansToMultiGroups::class,
        Commands\Utilities\ExportReservationPics::class
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
