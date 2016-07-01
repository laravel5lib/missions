<?php

namespace App\Providers;

use App\Models\v1\User;
use App\Models\v1\Reservation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        // Morph Map
        Relation::morphMap([
            'App\Models\v1\Fundraiser',
            'App\Models\v1\Group',
            'App\Models\v1\Trip',
            'App\Models\v1\User',
            'App\Models\v1\Reservation',
            'App\Models\v1\Assignment'
        ]);

        User::created(function ($user) {
            Mail::queue('emails.welcome', $user->toArray(), function ($message) use($user) {
                $message->from('mail@missions.me', 'Missions.Me');
                $message->sender('mail@missions.me', 'Missions.Me');
                $message->to($user->email, $user->name);
                $message->replyTo('go@missions.me', 'Missions.Me');
                $message->subject('Welcome to Missions.Me');
            });
        });

        Reservation::created(function ($reservation) {
            // needs to fire after costs sync
            $reservation->fundraisers()->create([
                'name' => 'General Fundraiser',
                'sponsor_type' => User::class,
                'sponsor_id' => $reservation->user_id,
                'goal_amount' => $reservation->costs()->sum('amount'),
                'expires_at' => $reservation->trip->started_at
            ]);
            $reservation->trip()->update([
               'spots' => $reservation->trip->spots - 1
            ]);

            // send confirmation email.
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
