<?php

use App\Models\v1\ProjectCause;
use App\Models\v1\ProjectType;
use App\Models\v1\Project;
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
        Bouncer::allow('admin')->to('manage costs', Reservation::class);
        Bouncer::allow('admin')->to('manage payments', Reservation::class);
        Bouncer::allow('admin')->to('manage requirements', Reservation::class);
        Bouncer::allow('intern')->to('view', Reservation::class);
        Bouncer::allow('intern')->to('create', Reservation::class);
        Bouncer::allow('intern')->to('edit', Reservation::class);
        Bouncer::allow('intern')->to('manage costs', Reservation::class);
        Bouncer::allow('intern')->to('manage payments', Reservation::class);
        Bouncer::allow('intern')->to('manage requirements', Reservation::class);
        // projects
        Bouncer::allow('admin')->to('view', Project::class);
        Bouncer::allow('admin')->to('create', Project::class);
        Bouncer::allow('admin')->to('edit', Project::class);
        Bouncer::allow('admin')->to('delete', Project::class);
        Bouncer::allow('intern')->to('view', Project::class);
        // costs
        Bouncer::allow('admin')->to('view', \App\Models\v1\Cost::class);
        Bouncer::allow('admin')->to('create', \App\Models\v1\Cost::class);
        Bouncer::allow('admin')->to('edit', \App\Models\v1\Cost::class);
        Bouncer::allow('admin')->to('delete', \App\Models\v1\Cost::class);
        Bouncer::allow('intern')->to('view', \App\Models\v1\Cost::class);
        // project causes
        Bouncer::allow('admin')->to('view', ProjectCause::class);
        Bouncer::allow('admin')->to('create', ProjectCause::class);
        Bouncer::allow('admin')->to('edit', ProjectCause::class);
        Bouncer::allow('admin')->to('delete', ProjectCause::class);
        Bouncer::allow('intern')->to('view', ProjectCause::class);
        // projects
        Bouncer::allow('admin')->to('view', Project::class);
        Bouncer::allow('admin')->to('create', Project::class);
        Bouncer::allow('admin')->to('edit', Project::class);
        Bouncer::allow('admin')->to('delete', Project::class);
        Bouncer::allow('intern')->to('view', Project::class);
        // transactions
        Bouncer::allow('admin')->to('view', \App\Models\v1\Transaction::class);
        Bouncer::allow('admin')->to('create', \App\Models\v1\Transaction::class);
        Bouncer::allow('admin')->to('edit', \App\Models\v1\Transaction::class);
        Bouncer::allow('admin')->to('delete', \App\Models\v1\Transaction::class);
        // funds
        Bouncer::allow('admin')->to('view', \App\Models\v1\Fund::class);
        Bouncer::allow('admin')->to('create', \App\Models\v1\Fund::class);
        Bouncer::allow('admin')->to('edit', \App\Models\v1\Fund::class);
        Bouncer::allow('intern')->to('view', \App\Models\v1\Fund::class);
        // donors
        Bouncer::allow('admin')->to('view', \App\Models\v1\Donor::class);
        Bouncer::allow('admin')->to('create', \App\Models\v1\Donor::class);
        Bouncer::allow('admin')->to('edit', \App\Models\v1\Donor::class);
        Bouncer::allow('admin')->to('delete', \App\Models\v1\Donor::class);
        Bouncer::allow('intern')->to('view', \App\Models\v1\Donor::class);
        // todos
        Bouncer::allow('admin')->to('modify', \App\Models\v1\Todo::class);
        // notes
        Bouncer::allow('admin')->to('modify', \App\Models\v1\Note::class);
        // uploads
        Bouncer::allow('admin')->to('view', \App\Models\v1\Upload::class);
        Bouncer::allow('admin')->to('create', \App\Models\v1\Upload::class);
        Bouncer::allow('admin')->to('edit', \App\Models\v1\Upload::class);
        Bouncer::allow('admin')->to('delete', \App\Models\v1\Upload::class);
    }
}
