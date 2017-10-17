<?php namespace App\Filters\v1;

use EloquentFilter\ModelFilter;
use Illuminate\Support\Facades\DB;

class Filter extends ModelFilter
{
    /**
     * Default sortable fields.
     *
     * @var array
     */
    public $sortable = [];

    /**
     * Default searchable fields.
     *
     * @var array
     */
    public $searchable = [];

    /**
     * @var
     */
    public $input;

    /**
     * By all tags.
     *
     * @param $tags
     * @return mixed
     */
    public function tags($tags)
    {
        return $this->withAllTags($tags)->get();
    }

    /**
     * Sort by field.
     *
     * @param $field
     * @return mixed
     */
    public function sort($field)
    {
        $param = preg_split('/\|+/', $field);
        $column = $param[0];
        $direction = isset($param[1]) ? $param[1] : 'asc';

        if (in_array($column, $this->sortable)) {
            return $this->orderBy($column, $direction);
        }

        return $this;
    }

    /**
     * Find by search terms.
     *
     * @param $terms
     * @return mixed
     */
    public function search($terms)
    {
        return $this->where(function ($query) use ($terms) {
            foreach ($this->searchable as $column) {
                if (strpos($column, '.')) {
                    list($field, $relationship) = explode(".", strrev($column), 2);

                    $query->orWhereHas(strrev($relationship), function ($col) use ($field, $terms) {

                        $keywords = explode(' ', $terms);

                        foreach ($keywords as $term) {
                            $col->orWhere(strrev($field), 'LIKE', "%$term%");
                        }
                    });
                } else {
                    $keywords = explode(' ', $terms);

                    foreach ($keywords as $term) {
                        $query->orWhere($column, 'LIKE', "%$term%");
                    }
                }
            }

            return $query;
        });
    }

    /**
     * At given creation date and after.
     *
     * @param $date
     */
    public function starts($date)
    {
        $this->whereDate('created_at', '>=', $date);
    }

    /**
     * At given creation date and before.
     *
     * @param $date
     */
    public function ends($date)
    {
        $this->whereDate('created_at', '<=', $date);
    }
}
