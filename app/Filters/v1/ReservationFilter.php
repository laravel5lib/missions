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
     * @return mixed
     */
    public function user($ids)
    {
        if($ids == []) return $this;

        return $this->whereIn('user_id', $ids);
    }

    /**
     * By shirt sizes.
     *
     * @param $shirtSizes
     * @return mixed
     */
    public function shirtSize($shirtSizes)
    {
        if($shirtSizes == []) return $this;

        return $this->whereIn('shirt_size', $shirtSizes);
    }

    /**
     * By gender.
     *
     * @param $gender
     * @return mixed
     */
    public function gender($gender)
    {
        if( !$gender) return $this;

        return $this->where('gender', $gender);
    }

    /**
     * My relationship status.
     *
     * @param $status
     * @return mixed
     */
    public function status($status)
    {
        if( !$status) return $this;

        return $this->where('status', $status);
    }

    /**
     * By reps.
     *
     * @param $ids
     * @return mixed
     */
    public function rep($ids)
    {
        if($ids == []) return $this;

        return $this->whereIn('rep_id', $ids);
    }

    /**
     * By trips.
     *
     * @param $ids
     * @return mixed
     */
    public function trip($ids)
    {
        if($ids == []) return $this;

        return $this->whereIn('trip_id', $ids);
    }

    /**
     * Between ages.
     *
     * @param $ages
     * @return mixed
     */
    public function age($ages)
    {
        if($ages == []) return $this;

        // $start needs to be the greater number to produce a year earlier than end
        $start = Carbon::now()->subYears($ages[1]);
        $end = Carbon::now()->subYears($ages[0]);

        return $this->whereBetween('birthday', [$start, $end]);
    }

    /**
     * Has a birthday this month.
     *
     * @return mixed
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
     * @return mixed
     */
    public function hasCompanions($hasCompanions)
    {
        if(!$hasCompanions) return $this;

        return $hasCompanions == 'yes' ?
            $this->has('companions') :
            $this->has('companions', '<', 1);
    }

    /**
     * Is currently active.
     *
     * @return mixed
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
     * @return mixed
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