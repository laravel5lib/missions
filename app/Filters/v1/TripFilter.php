<?php namespace App\Filters\v1;

use Carbon\Carbon;
use Dingo\Api\Auth\Auth;
use EloquentFilter\ModelFilter;

class TripFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [method1, method2]]
    *
    * @var array
    */
    public $relations = [];


    public function onlyPublished()
    {
        return $this->whereNotNull('published_at')
                    ->where('published_at', '<=', Carbon::now());
    }

    /**
     * Find by Groups.
     *
     * @param $ids
     * @return mixed
     */
    public function groups($ids)
    {
        return $this->whereIn('group_id', $ids);
    }

    /**
     * Find by Campaign.
     *
     * @param $id
     * @return mixed
     */
    public function campaign($id)
    {
        return $this->where('campaign_id', $id);
    }

    /**
     * Find by reps.
     *
     * @param $ids
     * @return mixed
     */
    public function reps($ids)
    {
        return $this->whereIn('rep_id', $ids);
    }

    /**
     * Find by countries.
     *
     * @param $codes
     * @return mixed
     */
    public function countries($codes)
    {
        return $this->whereIn('country_code', $codes);
    }

    /**
     * Find by types.
     *
     * @param $types
     * @return mixed
     */
    public function types($types)
    {
        return $this->whereIn('type', $types);
    }

    /**
     * Find by difficulties.
     *
     * @param $difficulties
     * @return mixed
     */
    public function difficulties($difficulties)
    {
        return $this->whereIn('difficulty', $difficulties);
    }

    /**
     * Find by reservations.
     *
     * @param $reservations
     * @return mixed
     */
    public function reservations($reservations)
    {
        if(count($reservations) < 2) return $this;

        return $this->has('reservations', '>=', $reservations[0])
                    ->has('reservations', '<=', $reservations[1]);
    }

    /**
     * Find by spots.
     *
     * @param $spots
     * @return $this
     */
    public function spots($spots)
    {
        if(count($spots) < 2) return $this;

        return $this->whereBetween('spots', $spots);
    }

    /**
     * Find by companions.
     *
     * @param $companions
     * @return $this
     */
    public function companions($companions)
    {
        if(count($companions) < 2) return $this;

        return $this->whereBetween('companion_limit', $companions);
    }

    /**
     * Find by existence of facilitators.
     *
     * @param $hasFacilitators
     * @return mixed
     */
    public function hasFacilitators($hasFacilitators)
    {
        return $hasFacilitators == 'yes' ?
            $this->has('facilitators') :
            $this->has('facilitators', '<', 1);
    }

    /**
     * Find by existence of notes.
     *
     * @param $hasNotes
     * @return mixed
     */
    public function hasNotes($hasNotes)
    {
        return $hasNotes == 'yes' ?
            $this->has('notes') :
            $this->has('notes', '<', 1);
    }

    /**
     * Find published trips.
     *
     * @param $published
     * @return mixed
     */
    public function published($published)
    {
        return $published == 'yes' ?
            $this->where('published_at', '<=', Carbon::now()) :
            $this->where('published_at', '>=', Carbon::now());
    }

    /**
     * Find by requirements.
     *
     * @param $requirements
     */
    public function requirements($requirements)
    {
        $this->whereHas('requirements', function ($r) use ($requirements)
        {
            return $r->whereIn('item', $requirements);
        });
    }

    /**
     * Find by cost types.
     *
     * @param $costs
     */
    public function costs($costs)
    {
        $this->whereHas('costs', function ($c) use ($costs)
        {
            return $c->whereIn('type', $costs);
        });
    }

    /**
     * Find by amounts.
     *
     * @param $amounts
     * @return $this
     */
    public function amounts($amounts)
    {
        if(count($amounts) < 2) return $this;

        $this->whereHas('costs', function ($c) use ($amounts)
        {
            return $c->whereBetween('amount', $amounts);
        });
    }

    /**
     * Find by start date range.
     *
     * @param $startDates
     * @return $this
     */
    public function starts($startDates)
    {
        if(count($startDates) < 2) return $this;

        return $this->whereBetween('started_at', $startDates);
    }

    /**
     * Find by end date range.
     *
     * @param $endDates
     * @return $this
     */
    public function ends($endDates)
    {
        if(count($endDates) < 2) return $this;

        return $this->whereBetween('ended_at', $endDates);
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
            // type
            $q->where('type', 'LIKE', "%$search%"
            // prospects
            )->orWhere('prospects', 'LIKE', "%$search%"
            // group name
            )->orWhereHas('group', function ($g) use ($search)
            {
                return $g->where('name', 'LIKE', "%$search%");
            }
            // campaign name
            )->orWhereHas('campaign', function ($c) use ($search)
            {
                return $c->where('name', 'LIKE', "%$search%");
            }
            // cost name
            )->orWhereHas('costs', function ($c) use ($search)
            {
                return $c->where('name', 'LIKE', "%$search%");
            }
            // deadline name
            )->orWhereHas('deadlines', function ($c) use ($search)
            {
                return $c->where('name', 'LIKE', "%$search%");
            }
            // facilitator's name
            )->orWhereHas('facilitators', function ($f) use ($search)
            {
                return $f->where('name', 'LIKE', "%$search%");
            });
        });
    }

    public function tags($tags)
    {
        $this->withAllTag($tags)->get();
    }

    /**
     * Sort by fields
     *
     * @param $sort
     * @return mixed
     */
    public function sort($sort)
    {
        $sortable = [
            'started_at', 'ended_at', 'published_at', 'created_at', 'updated_at'
        ];

        $param = preg_split('/\|+/', $sort);
        $field = $param[0];
        $direction = isset($param[1]) ? $param[1] : 'asc';

        if ( in_array($field, $sortable) )
            return $this->orderBy($field, $direction);

        return $this;
    }
}