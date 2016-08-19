<?php namespace App\Filters\v1;

use EloquentFilter\ModelFilter;

class DonationFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function donor($id)
    {
        return $this->where('donor_id', $id);
    }

    public function reservation($id)
    {
        return $this->where('designation_type', 'reservations')
                    ->where('designation_id', $id);
    }
}
