<?php namespace App\Filters\v1;

class ExamFilter extends Filter
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
        'email', 'created_at', 'updated_at', 'party_size'
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
     * Find by party size range
     *
     * @param $parties
     * @return $this|\Illuminate\Database\Eloquent\Builder
     */
    public function parties($parties)
    {
        if(count($parties) < 2) return $this;

        return $this->whereBetween('party_size', $parties);
    }

    /**
     * Find by gender
     *
     * @param $gender
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function gender($gender)
    {
        return $this->where('gender', $gender);
    }

    /**
     * Find by age group
     *
     * @param $age
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function age($age)
    {
        return $this->where('age_group', $age);
    }

    /**
     * Find by decision
     *
     * @param $decision
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function decision($decision)
    {
        return $decision == 'yes' ?
            $this->where('decision', true) :
            $this->where('decision', false);
    }
}