<?php namespace App\Filters\v1;

use EloquentFilter\ModelFilter;

class ReservationFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [method1, method2]]
    *
    * @var array
    */
    public $relations = [
        'trip' => ['groups', 'campaign'],
        'todos' => ['task', 'outstandingTasks'],
        'tags' => ['tags']
    ];

    public function user($ids)
    {
        return $this->whereIn('user_id', $ids);
    }

    public function shirtSize($shirtSizes)
    {
        return $this->whereIn('shirt_size', $shirtSizes);
    }

    public function gender($gender)
    {
        return $this->where('gender', $gender);
    }

    public function status($status)
    {
        return $this->where('status', $status);
    }

    public function rep($ids)
    {
        return $this->whereIn('rep_id', $ids);
    }

    public function trip($ids)
    {
        return $this->whereIn('trip_id', $ids);
    }

    public function age($ages)
    {
        $start = Carbon::now()->subYears($ages[0]);
        $end = Carbon::now()->subYears($ages[1]);
        return $this->whereBetween('birthday', [$start, $end]);
    }

    public function hasBirthday()
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        return $this->whereBetween('birthday', [$start, $end]);
    }

    public function hasCompanions($hasCompanions)
    {
        return $hasCompanions == 'yes' ?
            $this->has('companions') :
            $this->has('companions', '<', 1);
    }

    public function hasPassport($hasPassport)
    {
        return $hasPassport == 'yes' ?
            $this->whereNotNull('passport_id') :
            $this->whereNull('passport_id');
    }

    /**
     * Find by search
     *
     * @param $search
     * @return mixed
     */
    public function search($search)
    {
        return $this->where(function($q) use ($search)
        {
            return $q->where('given_names', 'LIKE', "%$search%")
                ->orWhere('surname', 'LIKE', "%$search%");
//                ->orWhere('amount', 'LIKE', "$search%");
        });
    }

    /**
     * Sort by fields
     *
     * @param $sort
     * @return mixed
     */
    public function sort($sort)
    {
        $sortable = [
            'birthday', 'updated_at', 'created_at',
            'given_names', 'surname'
        ];

        $param = preg_split('/\|+/', $sort);
        $field = $param[0];
        $direction = isset($param[1]) ? $param[1] : 'asc';

        if ( in_array($field, $sortable) )
            return $this->orderBy($field, $direction);

        return $this;
    }
}