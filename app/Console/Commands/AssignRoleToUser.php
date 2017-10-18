<?php

namespace App\Console\Commands;

use App\Models\v1\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AssignRoleToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:assign-role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a role to a user';

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
        $role = $this->choice('Which role do you want to assign?', Role::all()->pluck('name')->toArray());

        $email = $this->ask('Please enter the user\'s email address');

        $user = User::where('email', $email)->first();

        if ($user) {
            $user->assignRole($role);

            $this->info('Role successfully assigned to user!');
        }

        $this->error('A user with that email address could not be found.');
    }
}
