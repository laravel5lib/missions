<?php
namespace App\Filters\v1;

class TeamFilter extends Filter
{
    public $relations = [];
    public $sortable = ['callsign', 'created_at', 'updated_at'];
    public $searchable = [];

    public function search($query)
    {
        return $this->where('callsign', 'LIKE', "%$query%");
    }

    public function type($type)
    {
        return $this->whereHas('type', function ($query) use ($type) {
            return $query->where('name', strtolower(trim($type)));
        });
    }

    public function group($id)
    {
        return $this->whereHas('groups', function ($query) use ($id) {
            return $query->where('id', $id);
        });
    }

    public function campaign($id)
    {
        return $this->whereHas('campaigns', function ($query) use ($id) {
            return $query->where('id', $id);
        });
    }

    public function region($id)
    {
        return $this->whereHas('regions', function ($query) use ($id) {
            return $query->where('id', $id);
        });
    }

    /**
     * Filter by existing assignment
     */
    public function assigned($assignment)
    {
        $param = preg_split('/\|+/', $assignment);

        if (isset($param[1])) {
            return $this->whereHas(str_plural($param[0]), function ($query) use ($param) {
                return $query->where('id', '=', $param[1]);
            });
        }

        return $this->has(str_plural($param[0]));
    }

    /**
     * Filter by no assignment
     */
    public function unassigned($assignment)
    {
        $param = preg_split('/\|+/', $assignment);

        if (isset($param[1])) {
            return $this->whereHas(str_plural($param[0]), function ($query) use ($param) {
                return $query->where('id', '<>', $param[1]);
            });
        }

        return $this->has(str_plural($param[0]), '<', 1);
    }
}
