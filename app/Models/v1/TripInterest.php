<?php

namespace App\Models\v1;

use App\FilterTags;
use App\UuidForKey;
use EloquentFilter\Filterable;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TripInterest extends Model
{
    use UuidForKey, Filterable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'communication_preferences', 'trip_id', 'status'
    ];

    protected $casts = [
        'communication_preferences' => 'array'
    ];

    public function setCommunicationPreferencesAttribute($value)
    {
        $this->attributes['communication_preferences'] = json_encode($value);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Get all of the reservation's notes
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    /**
     * Get all of the reservation's todos
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function todos()
    {
        return $this->morphMany(Todo::class, 'todoable');
    }


    public static function buildQuery()
    {
        return QueryBuilder::for(static::class)
            ->allowedFilters([
                'name', 'status', 'email',
                Filter::exact('trip_id'),
                Filter::scope('campaign'),
                Filter::scope('trip_type'),
                Filter::scope('group'),
                Filter::scope('received_between'),
                Filter::scope('incomplete_task'),
                Filter::scope('complete_task'),
                Filter::custom('trip_tags', FilterTags::class)
            ])
            ->allowedIncludes(['trip.campaign', 'trip.group', 'trip.tags']);
    }

    public function scopeCurrent($query)
    {
        return $query->whereHas('trip', function ($trip) {
            return $trip->current();
        });
    }

    public function scopeCampaign($query, $campaignId)
    {
        return $query->whereHas('trip', function ($trip) use ($campaignId) {
            return $trip->where('campaign_id', $campaignId);
        });
    }

    public function scopeTripType($query, $type)
    {
        return $query->whereHas('trip', function ($trip) use ($type) {
            return $trip->where('type', $type);
        });
    }

    public function scopeGroup($query, $groupId)
    {
        return $query->whereHas('trip', function ($trip) use ($groupId) {
            return $trip->where('group_id', $groupId);
        });
    }

    public function scopeReceivedBetween($query, ...$dates)
    {
        $query->whereDate('created_at', '>=', $dates[0])
              ->whereDate('created_at', '<=', $dates[1]);
    }

    public function scopeIncompleteTask($query, $task)
    {
        return $query->whereHas('todos', function ($subQuery) use ($task) {
            return $subQuery->where('task', $task)->whereNull('completed_at');
        });
    }

    public function scopeCompleteTask($query, $task)
    {
        return $query->whereHas('todos', function ($subQuery) use ($task) {
            return $subQuery->where('task', $task)->whereNotNull('completed_at');
        });
    }
}
