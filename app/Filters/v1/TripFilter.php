<?php namespace App\Filters\v1;

use Carbon\Carbon;

class TripFilter extends Filter
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
        'started_at', 'ended_at', 'published_at', 'created_at', 'updated_at'
    ];

    /**
     * Fields that can be searched.
     *
     * @var array
     */
    public $searchable = [
        'type', 'prospects', 'group.name', 'campaign.name', 'costs.name',
        'deadlines.name', 'facilitators.name'
    ];

    /**
     * Only published.
     *
     * @return mixed
     */
    public function onlyPublished()
    {
        return $this->whereNotNull('published_at')
                    ->where('published_at', '<=', Carbon::now());
    }

    /**
     * Find by Groups.
     *
     * @param $ids
     * @return $this
     */
    public function groups($ids)
    {
        if($ids == []) return $this;

        return $this->whereIn('group_id', $ids);
    }

    /**
     * Find by Campaign.
     *
     * @param $id
     * @return $this
     */
    public function campaign($id)
    {
        if(!$id) return $this;

        return $this->where('campaign_id', $id);
    }

    /**
     * Find by reps.
     *
     * @param $ids
     * @return $this
     */
    public function reps($ids)
    {
        if($ids == []) return $this;

        return $this->whereIn('rep_id', $ids);
    }

    /**
     * Find by countries.
     *
     * @param $codes
     * @return $this
     */
    public function countries($codes)
    {
        if($codes == []) return $this;

        return $this->whereIn('country_code', $codes);
    }

    /**
     * Find by type.
     *
     * @param $type
     * @return $this
     */
    public function type($type)
    {
        if(! $type) return $this;

        return $this->where('type', $type);
    }

    /**
     * Find by status.
     *
     * @param $status
     * @return $this
     */
    public function status($status)
    {
        if(! $status) return $this;

        return $this->{$status}();
    }

    /**
     * Find by difficulties.
     *
     * @param $difficulties
     * @return $this
     */
    public function difficulties($difficulties)
    {
        if($difficulties == []) return $this;

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
        if ($hasFacilitators == []) return $this;

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
        if ($hasNotes == []) return $this;

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
        if (! $published) return $this;

        return $published == 'yes' ?
            $this->where('published_at', '<=', Carbon::now()) :
            $this->where('published_at', '>=', Carbon::now());
    }

    /**
     * Find by requirements.
     *
     * @param $requirements
     * @return $this
     */
    public function requirements($requirements)
    {
        if($requirements == []) return $this;

        return $this->whereHas('requirements', function ($r) use ($requirements)
        {
            return $r->whereIn('name', $requirements);
        });
    }

    /**
     * Find by cost types.
     *
     * @param $costs
     * @return $this
     */
    public function costs($costs)
    {
        if ($costs == []) return $this;

        return $this->whereHas('costs', function ($c) use ($costs)
        {
            return $c->whereIn('name', $costs);
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

        return $this->whereHas('costs', function ($c) use ($amounts)
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
}