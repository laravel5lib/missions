<?php
namespace App;

use App\Deposit;
use Carbon\Carbon;
use App\Models\v1\Fund;
use App\Models\v1\Deadline;
use App\ReservationFactory;
use App\Models\v1\Requirement;
use App\Traits\HasParentModel;
use Spatie\QueryBuilder\Filter;
use Illuminate\Support\Facades\DB;
use App\Models\v1\Trip as TripModel;
use Spatie\QueryBuilder\QueryBuilder;

class Trip extends TripModel
{
    use HasParentModel;
    
    public function getMorphClass()
    {
        return 'trips'; 
    } 
    
    public static function getAll()
    {
        return QueryBuilder::for(static::class)
            ->allowedFilters(
                Filter::exact('group_id'), 
                Filter::exact('campaign_id'), 
                Filter::exact('type'),
                Filter::scope('current'),
                Filter::scope('past'),
                Filter::scope('public'),
                Filter::scope('published')
            )
            ->allowedIncludes(['tags', 'group', 'priceables.cost', 'priceables.payments', 'campaign', 'requireables'])
            ->withCount('reservations');
    }

    public static function getById($id)
    {
        return QueryBuilder::for(static::class)
            ->allowedIncludes(['tags', 'group', 'priceables.cost', 'priceables.payments', 'campaign', 'requireables'])
            ->withCount('reservations')
            ->findOrFail($id);
    }

    /**
     * Syncronize all the trip's deadlines.
     *
     * @param $deadlines
     */
    public function syncDeadlines($deadlines)
    {
        if (! $deadlines) {
            return;
        }

        $ids = $this->deadlines()->pluck('id', 'id');

        foreach ($deadlines as $deadline) {
            if (! isset($deadline['id'])) {
                $deadline['id'] = null;
            }

            array_forget($ids, $deadline['id']);

            $this->deadlines()->updateOrCreate(['id' => $deadline['id']], $deadline);
        }

        if (! $ids->isEmpty()) {
            Deadline::destroy($ids);
        }
    }

    /**
     * Syncronize all the trip's requirements.
     *
     * @param $requirements
     */
    public function syncRequirements($requirements)
    {
        if (! $requirements) {
            return;
        }

        $ids = $this->requirements()->pluck('id', 'id');

        foreach ($requirements as $requirement) {
            if (! isset($requirement['id'])) {
                $requirement['id'] = null;
            }

            array_forget($ids, $requirement['id']);

            $this->requirements()->updateOrCreate(['id' => $requirement['id']], $requirement);
        }

        if (! $ids->isEmpty()) {
            Requirement::destroy($ids);
        }
    }

    /**
     * Syncronize all the trip's facilitators.
     *
     * @param $user_ids
     */
    public function syncFacilitators($user_ids = null)
    {
        if (is_null($user_ids)) {
            return;
        }

        $this->facilitators()->sync($user_ids);
    }

    /**
     * Check if trip is published.
     *
     * @return bool
     */
    public function isPublished()
    {
        if (is_null($this->published_at)) {
            return false;
        }

        return $this->published_at <= Carbon::now() ? true : false;
    }  
}