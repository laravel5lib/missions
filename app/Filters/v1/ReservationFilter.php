<?php namespace App\Filters\v1;

use Carbon\Carbon;

class ReservationFilter extends Filter
{
    use Manageable;

    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [method1, method2]]
    *
    * @var array
    */
    public $relations = [
        'trip' => ['groups', 'campaign', 'type']
    ];

    /**
     * Fields that can be sorted.
     *
     * @var array
     */
    public $sortable = [
        'birthday', 'updated_at', 'created_at',
        'given_names', 'surname', 'email', 'address',
        'city', 'zip', 'country_code', 'phone_one',
        'phone_two'
    ];

    /**
     * Fields that can be searched.
     *
     * @var array
     */
    public $searchable = [
        'given_names', 'surname', 'email', 'phone_one',
        'phone_two', 'zip', 'city', 'state'
    ];

    /**
     * By users.
     *
     * @param $ids
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function user($ids)
    {
        return $this->whereIn('user_id', $ids);
    }

    /**
     * By shirt sizes.
     *
     * @param $shirtSizes
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function shirtSize($shirtSizes)
    {
        return $this->whereIn('shirt_size', $shirtSizes);
    }

    /**
     * By gender.
     *
     * @param $gender
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function gender($gender)
    {
        return $this->where('gender', $gender);
    }

    /**
     * My relationship status.
     *
     * @param $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function status($status)
    {
        return $this->where('status', $status);
    }

    /**
     * By reps.
     *
     * @param $ids
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function rep($ids)
    {
        return $this->whereIn('rep_id', $ids);
    }

    /**
     * By trips.
     *
     * @param $ids
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function trip($ids)
    {
        return $this->whereIn('trip_id', $ids);
    }

    /**
     * Between ages.
     *
     * @param $ages
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function age($ages)
    {
        // $start needs to be the greater number to produce a year earlier than end
        $start = Carbon::now()->subYears($ages[1]);
        $end = Carbon::now()->subYears($ages[0]);

        return $this->whereBetween('birthday', [$start, $end]);
    }

    /**
     * Has a birthday this month.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function hasBirthday()
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        return $this->whereBetween('birthday', [$start, $end]);
    }

    /**
     * Has travel companions.
     *
     * @param $hasCompanions
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function hasCompanions($hasCompanions)
    {
        return $hasCompanions == 'yes' ?
            $this->has('companions') :
            $this->has('companions', '<', 1);
    }

    /**
     * Has a passport.
     *
     * @param $hasPassport
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function hasPassport($hasPassport)
    {
        return $hasPassport == 'yes' ?
            $this->whereNotNull('passport_id') :
            $this->whereNull('passport_id');
    }

    /**
     * Is currently active.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function current()
    {
        return $this->whereHas('trip', function($trip) {
            return $trip->current();
        });
    }

    /**
     * Is archived.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function archived()
    {
        return $this->whereHas('trip', function($trip) {
            return $trip->past();
        });
    }

    /**
     * Is dropped.
     *
     * @return mixed
     */
    public function dropped()
    {
        return $this->onlyTrashed();
    }
}