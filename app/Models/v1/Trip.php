<?php

namespace App\Models\v1;

use Carbon\Carbon;
use App\UuidForKey;
use App\Models\v1\Fund;
use App\Traits\HasTags;
use App\Traits\HasPricing;
use App\Traits\Promoteable;
use EloquentFilter\Filterable;
use App\Traits\HasRequirements;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use SoftDeletes, Filterable, UuidForKey, Promoteable, HasPricing, HasRequirements, HasTags;

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'campaign_id',
        'closed_at',
        'companion_limit',
        'country_code',
        'created_at',
        'description',
        'difficulty',       
        'ended_at',
        'group_id', 
        'public', 
        'published_at', 
        'prospects', 
        'rep_id', 
        'spots',
        'started_at', 
        'team_roles', 
        'thumb_src',
        'todos',        
        'type', 
        'updated_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'closed_at', 'created_at', 'deleted_at', 'ended_at', 'started_at', 'updated_at', 'published_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'companion_limit' => 'integer',
        'prospects' => 'array',
        'public' => 'boolean',
        'spots' => 'integer',
        'team_roles' => 'array',
        'todos' => 'array'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['status', 'starting_cost'];

    /**
     * Get the organization that owns the trip.
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
     * Get the trip's rep.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rep()
    {
        return $this->belongsTo(Representative::class, 'rep_id');
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

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->whereDate('published_at', '<=', now());
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
     * Get the status of the trip.
     *
     * @return string
     */
    public function getStatusAttribute()
    {
        // default status
        $status = 'active';

        // no published date indicates a draft status
        $status = is_null($this->published_at) ? 'unpublished' : $status;

        // a future published date means it's scheduled
        $status = ! is_null($this->published_at) &&
        $this->published_at->isFuture() ? 'scheduled' : $status;

        // close it if no more spots are available
        $status = $this->spots == 0 ? 'closed' : $status;

        // close it if past closing date
        $status = ! is_null($this->closed_at) && $this->closed_at->isPast() ? 'closed' : $status;

        return $status;
    }

    public function updateSpots($number = -1)
    {
        $this->spots = $this->spots + $number;

        $this->save();
    } 
}
