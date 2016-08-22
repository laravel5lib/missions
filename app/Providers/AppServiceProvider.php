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

            $active = $reservation->trip->activeCosts()->with('payments')->get();

            $maxDate = $active->where('type', 'incremental')->max('active_at');

            $dues = $active->reject(function ($value) use($maxDate) {
                return $value->type == 'incremental' && $value->active_at < $maxDate;
            })->flatMap(function ($cost) {
                return $cost->payments->map(function ($payment) {
                    return [
                        'payment_id' => $payment->id,
                        'due_at' => $payment->due_at,
                        'grace_period' => $payment->grace_period,
                        'outstanding_balance' => $payment->amount_owed,
                    ];
                })->all();
            });

            $reservation->addDues($dues);
            $reservation->syncRequirements($reservation->trip->requirements);
            $reservation->syncDeadlines($reservation->trip->deadlines);
            $reservation->addTodos($reservation->trip->todos);

            $name = 'Send ' . $reservation->name . ' to ' . country($reservation->trip->country_code);
            $reservation->fundraisers()->create([
                'name' => $name,
                'url' => str_replace(' ', '-', strtolower($name)) . '-' .substr($reservation->id, 0, 4),
                'description' => file_get_contents(resource_path('assets/sample_fundraiser.md')),
                'sponsor_type' => User::class,
                'sponsor_id' => $reservation->user_id,
                'goal_amount' => $reservation->getTotalCost(),
                'expires_at' => $reservation->trip->started_at,
                'banner_upload_id' => $reservation->trip->campaign->banner->id
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
