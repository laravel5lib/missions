<?php

namespace App\Providers;

use App\Events\UserWasCreated;
use App\Models\v1\Transaction;
use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Reservation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use League\Glide\Server;
use League\Glide\ServerFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
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

        User::created(function ($user) { event(new UserWasCreated($user)); });

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

            $reservation->fund()->create([
                'name' => generateFundName($reservation),
                'balance' => 0
            ]);

            $reservation->fund->fundraisers()->create([
                'name' => $name,
                'url' => str_slug(country($reservation->trip->country_code)).'-'.$reservation->trip->started_at->format('Y').'-'.str_random(4),
                'description' => file_get_contents(resource_path('assets/sample_fundraiser.md')),
                'sponsor_type' => 'users',
                'sponsor_id' => $reservation->user_id,
                'goal_amount' => $reservation->getTotalCost(),
                'started_at' => $reservation->created_at,
                'ended_at' => $reservation->trip->started_at,
                'banner_upload_id' => $reservation->trip->campaign->banner->id
            ]);

            $reservation->trip()->update([
               'spots' => $reservation->trip->spots - 1
            ]);

            // todo: send confirmation email.
        });

        Trip::created(function ($trip) {
            $trip->fund()->create([
                'name' => generateFundName($trip),
                'balance' => 0
            ]);
        });

        Transaction::created(function ($transaction) {
            $balance = $transaction->fund->balance + $transaction->amount;
            $transaction->fund->balance = $balance;
            $transaction->fund->save();
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
