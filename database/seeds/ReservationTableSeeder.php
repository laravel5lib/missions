<?php

use App\Events\ReservationWasCreated;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;

class ReservationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\Reservation::class, config('seeders.reservations'))->create()->each(function($r) {

            $r->companions()->save(factory(App\Models\v1\Companion::class)->make());

            $r->notes()->save(factory(App\Models\v1\Note::class)->make());

            $r->tag(['vip', 'missionary']);

            $active = $r->trip->activeCosts()->get();

            $maxDate = $active->where('type', 'incremental')->max('active_at');

            $costs = $active->reject(function ($value) use ($maxDate)
            {
                return $value->type == 'incremental' && $value->active_at < $maxDate;
            });

            $r->syncCosts($costs);
            $r->syncRequirements($r->trip->requirements);
            $r->syncDeadlines($r->trip->deadlines);
            $r->addTodos($r->trip->todos);

            $fund = $r->fund()->create([
                'name' => generateFundName($r),
                'balance' => 0
            ]);

            $donor = factory(App\Models\v1\Donor::class)->create([
                'account_id' => $r->user_id,
                'account_type' => 'users',
                'name' => $r->user->name
            ]);

            factory(App\Models\v1\Transaction::class, 'donation')->create([
                'amount' => 100,
                'description' => 'Reservation Payment',
                'fund_id' => $fund->id,
                'donor_id' => $donor->id
            ]);

            $slug = str_slug(country($r->trip->country_code)).
                '-'.
                $r->trip->started_at->format('Y').
                '-'.
                str_random(4);

            $r->fund->fundraisers()->create([
                'name' => generateFundraiserName($r),
                'url' => $slug,
                'description' => file_get_contents(resource_path('assets/sample_fundraiser.md')),
                'sponsor_type' => 'users',
                'sponsor_id' => $r->user_id,
                'goal_amount' => $r->getTotalCost(),
                'started_at' => $r->created_at,
                'ended_at' => $r->trip->started_at,
                'banner_upload_id' => $r->trip->campaign->banner->id
            ]);
        });

        // Give the admin user at least one reservation.
        $user = App\Models\v1\User::where('name', 'Admin')->firstOrFail();

        factory(App\Models\v1\Reservation::class)->create(['user_id' => $user->id])->each(function($r) {

            $r->companions()->save(factory(App\Models\v1\Companion::class)->make());

            $r->notes()->save(factory(App\Models\v1\Note::class)->make());

            $r->tag(['vip', 'missionary']);

            $active = $r->trip->activeCosts()->get();

            $maxDate = $active->where('type', 'incremental')->max('active_at');

            $costs = $active->reject(function ($value) use ($maxDate)
            {
                return $value->type == 'incremental' && $value->active_at < $maxDate;
            });

            $r->syncCosts($costs);
            $r->syncRequirements($r->trip->requirements);
            $r->syncDeadlines($r->trip->deadlines);
            $r->addTodos($r->trip->todos);

            $fund = $r->fund()->create([
                'name' => generateFundName($r),
                'balance' => 0
            ]);

            $donor = factory(App\Models\v1\Donor::class)->create([
                'account_id' => $r->user_id,
                'account_type' => 'users',
                'name' => $r->user->name
            ]);

            factory(App\Models\v1\Transaction::class, 'donation')->create([
                'amount' => 100,
                'description' => 'Reservation Payment',
                'fund_id' => $fund->id,
                'donor_id' => $donor->id
            ]);

            $slug = str_slug(country($r->trip->country_code)).
                '-'.
                $r->trip->started_at->format('Y').
                '-'.
                str_random(4);

            $r->fund->fundraisers()->create([
                'name' => generateFundraiserName($r),
                'url' => $slug,
                'description' => file_get_contents(resource_path('assets/sample_fundraiser.md')),
                'sponsor_type' => 'users',
                'sponsor_id' => $r->user_id,
                'goal_amount' => $r->getTotalCost(),
                'started_at' => $r->created_at,
                'ended_at' => $r->trip->started_at,
                'banner_upload_id' => $r->trip->campaign->banner->id
            ]);
        });
    }
}
