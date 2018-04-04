<?php

namespace App\Console\Commands;

use App\Models\v1\Reminder;
use Illuminate\Console\Command;

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send any scheduled reminders to subscribers.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Reminder::where('remindable_type', 'fundraisers')
            ->get()
            ->each(function ($reminder) {
                $reminder->send();
                $reminder->delete();
                $this->info('Reminder sent to ' . $reminder->email);
        });

        $this->info('Done. All scheduled reminders sent.');
    }
}
