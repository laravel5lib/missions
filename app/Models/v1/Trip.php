<?php

namespace App\Models\v1;

use App\UuidForKey;
use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use SoftDeletes, Filterable, UuidForKey;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trips';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'group_id', 'campaign_id', 'rep_id', 'spots',
        'country', 'type', 'difficulty', 'thumb_src',
        'started_at', 'ended_at', 'description', 'todos',
        'companion_limit', 'published_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'started_at', 'ended_at', 'created_at',
        'updated_at', 'deleted_at', 'published_at'
    ];

    /**
     * All of the relationships to be touched.
     * Update the parent's timestamp.
     *
     * @var array
     */
    protected $touches = ['group', 'campaign'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'todos' => 'array'
    ];

    /**
     * Indicates if the model should be timestamped
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the group that owns the trip.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the campaign that owns the trip.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get all the trip's prospects.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function prospects()
    {
        return $this->belongsToMany(Prospect::class);
    }

    /**
     * Get all the trip's costs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function costs()
    {
        return $this->morphMany(Cost::class, 'cost_assignable');
    }

    /**
     * Get all the trip's deadlines.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function deadlines()
    {
        return $this->morphMany(Deadline::class, 'deadline_assignable');
    }

    /**
     * Get all the trip's requirements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function requirements()
    {
        return $this->morphMany(Requirement::class, 'requirable');
    }

    /**
     * Get all the trip's facilitators
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function facilitators()
    {
        return $this->hasMany(Facilitator::class);
    }

    /**
     * Get all the trip's notes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    /**
     * Get all the trip's reservations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Set the trip's todos list.
     *
     * @param $value
     */
    public function setTodosAttribute($value)
    {
        $this->attributes['todos'] = json_encode($value);
    }

    /**
     * Syncronize all the trip's deadlines.
     *
     * @param $deadlines
     */
    public function syncDeadlines($deadlines)
    {
        if ( ! $deadlines) return;

        $ids = $this->deadlines()->lists('id', 'id');

        foreach($deadlines as $deadline)
        {
            if( ! isset($deadline['id'])) $deadline['id'] = null;

            array_forget($ids, $deadline['id']);

            $this->deadlines()->updateOrCreate(['id' => $deadline['id']], $deadline);
        }

        if( ! $ids->isEmpty()) Deadline::destroy($ids);
    }

    /**
     * Syncronize all the trip's costs.
     *
     * @param $costs
     */
    public function syncCosts($costs)
    {
        if ( ! $costs) return;

        $ids = $this->costs()->lists('id', 'id');

        foreach($costs as $cost)
        {
            if( ! isset($cost['id'])) $cost['id'] = null;

            array_forget($ids, $cost['id']);

            $this->costs()->updateOrCreate(['id' => $cost['id']], $cost);
        }

        if( ! $ids->isEmpty()) Cost::destroy($ids);
    }

    /**
     * Syncronize all the trip's requirements.
     *
     * @param $requirements
     */
    public function syncRequirements($requirements)
    {
        if ( ! $requirements) return;

        $ids = $this->requirements()->lists('id', 'id');

        foreach($requirements as $requirement)
        {
            if( ! isset($requirement['id'])) $requirement['id'] = null;

            array_forget($ids, $requirement['id']);

            $this->requirements()->updateOrCreate(['id' => $requirement['id']], $requirement);
        }

        if( ! $ids->isEmpty()) Requirement::destroy($ids);
    }

    public function isPublished()
    {
        if (is_null($this->published_at)) return false;
        
        return $this->published_at <= Carbon::now() ? true : false;
    }
}
