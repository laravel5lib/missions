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
        // Admin defaults
        Bouncer::allow('admin')->to('view-admin');
        // users
        Bouncer::allow('admin')->to('view', User::class);
        Bouncer::allow('admin')->to('create', User::class);
        Bouncer::allow('admin')->to('edit', User::class);
        Bouncer::allow('admin')->to('delete', User::class);
        // groups
        Bouncer::allow('admin')->to('view', Group::class);
        Bouncer::allow('admin')->to('create', Group::class);
        Bouncer::allow('admin')->to('edit', Group::class);
        Bouncer::allow('admin')->to('delete', Group::class);
        // campaigns
        Bouncer::allow('admin')->to('view', Campaign::class);
        Bouncer::allow('admin')->to('create', Campaign::class);
        Bouncer::allow('admin')->to('edit', Campaign::class);
        Bouncer::allow('admin')->to('delete', Campaign::class);
        // trips
        Bouncer::allow('admin')->to('view', Trip::class);
        Bouncer::allow('admin')->to('create', Trip::class);
        Bouncer::allow('admin')->to('edit', Trip::class);
        Bouncer::allow('admin')->to('delete', Trip::class);
        // reservations
        Bouncer::allow('admin')->to('view', Reservation::class);
        Bouncer::allow('admin')->to('create', Reservation::class);
        Bouncer::allow('admin')->to('edit', Reservation::class);
        Bouncer::allow('admin')->to('delete', Reservation::class);
        // uploads
        Bouncer::allow('admin')->to('view', Upload::class);
        Bouncer::allow('admin')->to('create', Upload::class);
        Bouncer::allow('admin')->to('edit', Upload::class);
        Bouncer::allow('admin')->to('delete', Upload::class);
    }
}
