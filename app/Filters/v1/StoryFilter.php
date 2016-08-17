<?php namespace App\Filters\v1;

use EloquentFilter\ModelFilter;

class StoryFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function group($id)
    {
        return $this->where('author_id', $id)
            ->where('author_type', 'groups');
    }

    public function user($id)
    {
        return $this->where('author_id', $id)
            ->where('author_type', 'users');
    }

    public function fundraiser($id)
    {
        return $this->where('author_id', $id)
            ->where('author_type', 'fundraisers');
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
            return $q->where('title', 'LIKE', "%$search%")
                ->orWhere('content', 'LIKE', "%$search%");
        });
    }
}
