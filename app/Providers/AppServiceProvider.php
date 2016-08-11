<?php

namespace App\Providers;

use App\Models\v1\User;
use App\Models\v1\Reservation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use League\Glide\Server;
use League\Glide\ServerFactory;
use Silber\Bouncer\BouncerFacade as Bouncer;

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
            'App\Models\v1\Assignment',
            'App\Models\v1\Campaign',
            'App\Models\v1\Upload'
        ]);

        // Send welcome emails when user is created.
//        User::created(function ($user) {
//            Mail::queue('emails.welcome', $user->toArray(), function ($message) use($user) {
//                $message->from('mail@missions.me', 'Missions.Me');
//                $message->sender('mail@missions.me', 'Missions.Me');
//                $message->to($user->email, $user->name);
//                $message->replyTo('go@missions.me', 'Missions.Me');
//                $message->subject('Welcome to Missions.Me');
//            });
//        });

        Reservation::created(function ($reservation) {

            $active = $reservation->trip->activeCosts;

            $maxDate = $active->where('type', 'incremental')->max('active_at');

            $costs = $active->reject(function ($value) use($maxDate) {
                return $value->type == 'incremental' && $value->active_at < $maxDate;
            });

            $reservation->syncCosts($costs);
            $reservation->syncRequirements($reservation->trip->requirements);
            $reservation->syncDeadlines($reservation->trip->deadlines);
            $reservation->addTodos($reservation->trip->todos);

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

            // todo: send confirmation email.
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // register and configure media server.
        $this->app->singleton(Server::class, function () {

            return ServerFactory::create([
                'source' => Storage::disk('s3')->getDriver(),
                'cache' => Storage::disk('local')->getDriver(),
                'source_path_prefix' => 'images',
                'cache_path_prefix' => 'images/.cache',
                'base_url' => 'api/images',
                'max_image_size' => 2000*2000
            ]);
        });
    }
}
