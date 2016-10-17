<?php namespace App\Filters\v1;

class DecisionFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [method1, method2]]
    *
    * @var array
    */
    public $relations = [];

    /**
     * Fields that can be sorted.
     *
     * @var array
     */
    public $sortable = [
        'name', 'gender', 'age_group', 'phone',
        'email', 'created_at', 'updated_at',
        'lat', 'long'
    ];

    /**
     * Fields that can be searched.
     *
     * @var array
     */
    public $searchable = [
        'name', 'email', 'phone', 'lat', 'long', 'region.name',
        'reservation.given_names', 'reservation.surname'
    ];

    /**
     * Find by gender
     *
     * @param $gender
     * @return mixed
     */
    public function gender($gender)
    {
        return $this->where('gender', $gender);
    }

    /**
     * Find by age group
     *
     * @param $age
     * @return mixed
     */
    public function age($age)
    {
        return $this->where('age_group', $age);
    }

    /**
     * Find by decision
     *
     * @param $decision
     * @return mixed
     */
    public function decision($decision)
    {
        return $decision == 'yes' ?
            $this->where('decision', true) :
            $this->where('decision', false);
    }
}