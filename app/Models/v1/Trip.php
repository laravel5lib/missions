<?php

namespace App\Models\v1;

use Carbon\Carbon;
use App\UuidForKey;
use App\Traits\Promoteable;
use Conner\Tagging\Taggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use SoftDeletes, Filterable, UuidForKey, Taggable, Promoteable;

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
        'companion_limit', 'published_at', 'closed_at',
        'prospects', 'public', 'team_roles', 'country_code',
        'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'started_at', 'ended_at', 'created_at',
        'updated_at', 'deleted_at', 'published_at',
        'closed_at'
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
        'todos' => 'array',
        'prospects' => 'array',
        'team_roles' => 'array'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['status'];

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
     * Get all the trip's costs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function costs()
    {
        return $this->morphMany(Cost::class, 'cost_assignable');
    }

    /**
     * Get all the trip's active costs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activeCosts()
    {
        return $this->costs()->active();
    }

    /**
     * Get the trip's user rep.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rep()
    {
        return $this->belongsTo(User::class, 'rep_id');
    }

    /**
     * Get the trip's promotionals.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function promotionals()
    {
        return $this->morphMany(Promotional::class, 'promoteable');
    }

    /**
     * Get the current starting cost for the trip.
     *
     * @return int
     */
    public function getStartingCostAttribute()
    {
        $incremental = $this->activeCosts()->type('incremental')->first();

        $amount = $incremental ? $incremental->amount : 0;

        return $amount + $this->activeCosts()->type('static')->sum('amount'); // convert to dollars
    }

    public function startingCostInDollars()
    {
        return number_format($this->starting_cost/100, 2, '.', '');
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
        return $this->morphMany(Requirement::class, 'requester');
    }

    /**
     * Get all the trip's facilitators
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function facilitators()
    {
        return $this->belongsToMany(User::class, 'facilitators')
                    ->withTimestamps();
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
     * Get the trip's fund.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function fund()
    {
        return $this->morphOne(Fund::class, 'fundable');
    }

    /**
     * Get all the trip's interests.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function interests()
    {
        return $this->hasMany(TripInterest::class);
    }

    /**
     * Set the trip's todos list.
     *
     * @param $value
     */
    public function setTodosAttribute($value)
    {
        $todos = collect($value)->transform(function ($item) {
            return trim(strtolower($item));
        });

        $this->attributes['todos'] = json_encode($todos);
    }

    /**
     * Set the trip's team roles list.
     *
     * @param $value
     */
    public function setTeamRolesAttribute($value)
    {
        $this->attributes['team_roles'] = json_encode($value);
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
     * Syncronize all the trip's costs.
     *
     * @param $costs
     */
    public function syncCosts($costs)
    {
        if (! $costs) {
            return;
        }

        $ids = $this->costs()->pluck('id', 'id');

        foreach ($costs as $cost) {
            if (! isset($cost['id'])) {
                $cost['id'] = null;
            }

            array_forget($ids, $cost['id']);

            $this->costs()->updateOrCreate(['id' => $cost['id']], $cost);
        }

        if (! $ids->isEmpty()) {
            Cost::destroy($ids);
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

    /**
     * Return the trip's difficulty.
     *
     * @param $value
     * @return mixed
     */
    public function getDifficultyAttribute($value)
    {
        return str_replace('_', ' ', $value);
    }

    /**
     * Get the status of the trip.
     *
     * @return string
     */
    public function getStatusAttribute()
    {
        // default status
        $status = 'active';

        // no published date indicates a draft status
        $status = is_null($this->published_at) ? 'draft' : $status;

        // a future published date means it's scheduled
        $status = ! is_null($this->published_at) &&
        $this->published_at->isFuture() ? 'scheduled' : $status;

        // close it if no more spots are available
        $status = $this->spots == 0 ? 'closed' : $status;

        // close it if past closing date
        $status = $this->closed_at->isPast() ? 'closed' : $status;

        return $status;
    }

    public function updateSpots($number = -1)
    {
        $this->spots = $this->spots + $number;

        $this->save();
    }

    public function scopeCurrent($query)
    {
        return $query->where('ended_at', '>=', Carbon::now());
    }

    public function scopePast($query)
    {
        return $query->where('ended_at', '<', Carbon::now());
    }

    public function scopeActive($query)
    {
        return $query->where('closed_at', '>', Carbon::now())
                    ->where('spots', '>', 0)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', Carbon::now());
    }

    public function scopeClosed($query)
    {
        return $query->where('closed_at', '<=', Carbon::now())
                    ->orWhere('spots', 0);
    }

    public function scopeScheduled($query)
    {
        return $query->whereNotNull('published_at')
                    ->where('published_at', '>', Carbon::now());
    }

    public function scopeDraft($query)
    {
        return $query->whereNull('published_at');
    }

    public function scopePublic($query)
    {
        return $query->where('public', true);
    }

    public function scopePrivate($query)
    {
        return $query->where('public', false);
    }
}
