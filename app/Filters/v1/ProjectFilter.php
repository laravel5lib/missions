<?php namespace App\Filters\v1;

use App\Models\v1\User;

class ProjectFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    /**
     * Default sortable fields.
     *
     * @var array
     */
    public $sortable = ['name', 'created_at'];

    /**
     * Default searchable fields.
     *
     * @var array
     */
    public $searchable = ['name', 'user.name', 'group.name', 'initiative.type'];

    /**
     * Is currently active.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function current()
    {
        return $this->new();
    }

    /**
     * Is archived.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function archived()
    {
        return $this->old();
    }

    /**
     * Is funded.
     *
     * @return mixed
     */
    public function funded()
    {
        return $this->funded();
    }

    /**
     * By groups managed by the user.
     *
     * @param $user_id
     * @return mixed
     */
    public function manager($user_id)
    {
        $user = User::findOrFail($user_id);

        $groups = $user->managing()->pluck('id');

        return $this->where(function($query) use($groups) {
            return $query->where('sponsor_type', 'groups')->whereIn('sponsor_id', $groups);
        })->orWhere(function($query) use($user_id) {
            return $query->where('sponsor_type', 'users')->where('sponsor_id', $user_id);
        });
    }

    /**
     * By user sponsor.
     *
     * @param $id
     * @return mixed
     */
    public function user($id)
    {
        return $this->where('sponsor_type', 'users')
                    ->where('sponsor_id', $id);
    }

    /**
     * By group sponsors.
     *
     * @param $ids
     * @return mixed
     */
    public function groups($ids)
    {
        return $this->where('sponsor_type', 'groups')
                    ->whereIn('sponsor_id', $ids);
    }

    /**
     * Belongs to cause.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function cause($id)
    {
        return $this->whereHas('initiative', function($initiative) use($id) {
           return $initiative->where('project_cause_id', $id);
        });
    }
}
