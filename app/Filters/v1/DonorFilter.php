<?php namespace App\Filters\v1;

use EloquentFilter\ModelFilter;

class DonorFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function reservation($id)
    {
        return $this->whereHas('donations', function($donation) use($id) {
            $donation->where('designation_type', 'reservations')
                ->where('designation_id', $id);
        });
    }

    public function user($id)
    {
        return $this->where('account_holder_id', $id)
            ->where('account_holder_type', 'users');
    }

    public function group($id)
    {
        return $this->where('account_holder_id', $id)
            ->where('account_holder_type', 'groups');
    }
}
