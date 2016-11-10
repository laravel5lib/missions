<?php

use App\Models\v1\User;
use App\Models\v1\Campaign;
use App\Models\v1\Group;
use App\Models\v1\Reservation;
use App\Models\v1\Trip;
use App\Models\v1\Upload;
use Illuminate\Database\Seeder;

class BouncerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // manager
        // facilitator

        Bouncer::allow('member')->to('access-dashboard');
        Bouncer::allow('intern')->to('access-admin');
        Bouncer::allow('intern')->to('access-dashboard');
        Bouncer::allow('admin')->to('access-admin');
        Bouncer::allow('admin')->to('access-dashboard');

        // users
        Bouncer::allow('admin')->to('view', User::class);
        Bouncer::allow('admin')->to('create', User::class);
        Bouncer::allow('admin')->to('edit', User::class);
        Bouncer::allow('admin')->to('delete', User::class);
        Bouncer::allow('intern')->to('view', User::class);
        Bouncer::allow('intern')->to('create', User::class);
        Bouncer::allow('intern')->to('edit', User::class);
        // groups
        Bouncer::allow('admin')->to('view', Group::class);
        Bouncer::allow('admin')->to('create', Group::class);
        Bouncer::allow('admin')->to('edit', Group::class);
        Bouncer::allow('admin')->to('delete', Group::class);
        Bouncer::allow('intern')->to('view', Group::class);
        // campaigns
        Bouncer::allow('admin')->to('view', Campaign::class);
        Bouncer::allow('admin')->to('create', Campaign::class);
        Bouncer::allow('admin')->to('edit', Campaign::class);
        Bouncer::allow('admin')->to('delete', Campaign::class);
        Bouncer::allow('intern')->to('view', Campaign::class);
        // trips
        Bouncer::allow('admin')->to('view', Trip::class);
        Bouncer::allow('admin')->to('create', Trip::class);
        Bouncer::allow('admin')->to('edit', Trip::class);
        Bouncer::allow('admin')->to('delete', Trip::class);
        Bouncer::allow('intern')->to('view', Trip::class);
        // reservations
        Bouncer::allow('admin')->to('view', Reservation::class);
        Bouncer::allow('admin')->to('create', Reservation::class);
        Bouncer::allow('admin')->to('edit', Reservation::class);
        Bouncer::allow('admin')->to('delete', Reservation::class);
        Bouncer::allow('intern')->to('view', Reservation::class);
        Bouncer::allow('intern')->to('create', Reservation::class);
        Bouncer::allow('intern')->to('edit', Reservation::class);
        // uploads
        Bouncer::allow('admin')->to('view', Upload::class);
        Bouncer::allow('admin')->to('create', Upload::class);
        Bouncer::allow('admin')->to('edit', Upload::class);
        Bouncer::allow('admin')->to('delete', Upload::class);
        // todos
        Bouncer::allow('admin')->to('modify-todos');
        // notes
        Bouncer::allow('admin')->to('modify-notes');
    }
}
