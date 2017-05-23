<?php 
namespace App\Filters\v1;

use Carbon\Carbon;

class TeamFilter extends Filter
{
    public $relations = [];
    public $sortable = ['callsign', 'created_at', 'updated_at'];
    public $searchable = ['callsign'];

    /**
     * Filter by Group ID
     */
    public function group($id)
    {
        return $this->whereHas('groups', function($group) use($id) {
            return $group->where('id', $id);
        });
    }
}