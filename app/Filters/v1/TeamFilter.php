<?php 
namespace App\Filters\v1;

class TeamFilter extends Filter
{
    public $relations = [];
    public $sortable = ['callsign', 'created_at', 'updated_at'];
    public $searchable = ['callsign'];

    public function groups($id)
    {
        return $this->whereHas('groups', function($query) use($id) {
            return $query->where('id', $id);
        });
    }

    public function campaigns($id)
    {
        return $this->whereHas('campaigns', function($query) use($id) {
            return $query->where('id', $id);
        });
    }
}