<?php

namespace App\Console\Commands;

use App\Models\v1\User;
use Illuminate\Console\Command;

class RemoveRoleFromUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:remove-role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove a role from the user.';

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
        $email = $this->ask('Please enter the user\'s email address');

        $user = User::where('email', $email)->first();

        if ($user) {
            $role = $this->choice('Which role do you want to remove?', $user->roles->pluck('name')->toArray());

            $user->removeRole($role);

            $this->info('Role successfully removed from the user!');
        }

        $this->error('A user with that email address could not be found.');
    }
}
