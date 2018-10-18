<?php

namespace App\Models\v1;

use App\FilterTags;
use App\UuidForKey;
use EloquentFilter\Filterable;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class TripInterest extends Model
{
    use UuidForKey, Filterable, SoftDeletes, LogsActivity;

    /**
     * The attributes that can be mass assigned.
     * 
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'communication_preferences', 'trip_id', 'status'
    ];

    /**
     * Indicates if all fillable attributes should be logged. 
     * 
     * @var boolean
     */
    protected static $logFillable = true;

    /**
     * The model events that should be logged.
     * 
     * @var array
     */
    protected static $recordEvents = ['updated', 'deleted'];

    /**
     * The attributes cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'communication_preferences' => 'array'
    ];

    /**
     * Set the communication preferences attribute.
     * 
     * @param array $value
     */
    public function setCommunicationPreferencesAttribute($value)
    {
        $this->attributes['communication_preferences'] = json_encode($value);
    }

    /**
     * The trip the interest belongs to.
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * The interest's notes
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    /**
     * The interest's todos
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function todos()
    {
        return $this->morphMany(Todo::class, 'todoable');
    }

    /**
     * Build a query to get interests.
     * 
     * @return Illuminate\Database\Eloquent\Builder
     */
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

    /**
     * Scope query to interests belonging to current trips.
     * 
     * @param  Builder $query
     * @return Builder
     */
    public function scopeCurrent($query)
    {
        return $query->whereHas('trip', function ($trip) {
            return $trip->current();
        });
    }

    /**
     * Scope query to interests belonging to the campaign.
     * 
     * @param  Builder $query
     * @param  String $campaignId
     * @return Builder
     */
    public function scopeCampaign($query, $campaignId)
    {
        return $query->whereHas('trip', function ($trip) use ($campaignId) {
            return $trip->where('campaign_id', $campaignId);
        });
    }

    /**
     * Scope query to interests belonging to a specific trip type.
     * 
     * @param  Builder $query
     * @param  String $type
     * @return Builder
     */
    public function scopeTripType($query, $type)
    {
        return $query->whereHas('trip', function ($trip) use ($type) {
            return $trip->where('type', $type);
        });
    }

    /**
     * Scope query to interests belonging to a specific group.
     * 
     * @param  Builder $query
     * @param  String $groupId
     * @return Builder
     */
    public function scopeGroup($query, $groupId)
    {
        return $query->whereHas('trip', function ($trip) use ($groupId) {
            return $trip->where('group_id', $groupId);
        });
    }

    /**
     * Scope query to interests created between two dates.
     * 
     * @param  Builder $query
     * @param  Array|String $dates
     * @return Builder
     */
    public function scopeReceivedBetween($query, ...$dates)
    {
        $query->whereDate('created_at', '>=', $dates[0])
              ->whereDate('created_at', '<=', $dates[1]);
    }

    /**
     * Scope query to interests with incomplete tasks.
     * 
     * @param  Builder $query
     * @param  String $task
     * @return Builder
     */
    public function scopeIncompleteTask($query, $task)
    {
        return $query->whereHas('todos', function ($subQuery) use ($task) {
            return $subQuery->where('task', $task)->whereNull('completed_at');
        });
    }

    /**
     * Scope query to interests with completed.
     * 
     * @param  Builder $query
     * @param  String $campaignId
     * @return Builder
     */
    public function scopeCompleteTask($query, $task)
    {
        return $query->whereHas('todos', function ($subQuery) use ($task) {
            return $subQuery->where('task', $task)->whereNotNull('completed_at');
        });
    }
}
