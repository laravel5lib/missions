<?php

namespace App\Models\v1;

use App\UuidForKey;
use App\Models\v1\Team;
use App\Models\v1\Price;
use App\Models\v1\Region;
use App\Traits\Rewardable;
use App\Traits\Promoteable;
use App\Models\v1\Requirement;
use EloquentFilter\Filterable;
use App\Models\v1\TripInterest;
use App\Models\v1\TripTemplate;
use App\Traits\HasRequirements;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Campaign extends Model implements HasMedia
{
    use SoftDeletes, Filterable, UuidForKey, Promoteable, HasMediaTrait, HasRequirements;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'campaigns';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'country_code', 'short_desc',
        'started_at', 'ended_at', 'published_at',
        'page_src', 'created_at', 'updated_at', 'deleted_at',
        'avatar_upload_id', 'publish_squads', 'publish_regions',
        'publish_rooms', 'publish_transports', 'reservations_locked'
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

    protected $casts = [
        'publish_squads' => 'boolean',
        'publish_regions' => 'boolean',
        'publish_rooms' => 'boolean', 
        'publish_transports' => 'boolean', 
        'reservations_locked' => 'boolean'
    ];

    /**
     * All of the relationships to be touched.
     * Update the parent's timestamp.
     *
     * @var array
     */
    protected $touches = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('optimized')
             ->optimize()
             ->fit(Manipulations::FIT_MAX, 400, 400)
             ->nonQueued();
    }

    /**
     * Set the campaign's countries.
     *
     * @param $value
     */
    public function setCountriesAttribute($value)
    {
        $this->attributes['countries'] = json_encode($value);
    }

    /**
     * Set the campaign's name.
     *
     * @param $value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim($value);
    }

    /**
     * Set the campaign's description.
     *
     * @param $value
     */
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = trim($value);
    }

    /**
     * Get the campaign's name.
     *
     * @param $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    /**
     * Get the Campaign's Status.
     *
     * @return string
     */
    public function getStatusAttribute()
    {
        if ($this->published_at and $this->published_at->isFuture()) {
            return 'Scheduled';
        }

        if ($this->published_at and $this->published_at->isPast()) {
            return 'Public';
        }

        return 'Private';
    }

    /**
     * Get a campaign's costs
     *
     * @return Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function costs()
    {
        return $this->morphMany(Cost::class, 'cost_assignable');
    }

    public function regions()
    {
        return $this->hasMany(Region::class);
    }

    public function teams()
    {
        return $this->morphToMany(Team::class, 'teamable');
    }

    /**
     * Get all the campaign's trips.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    /**
     * Get all the campaign's trip templates.
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tripTemplates()
    {
        return $this->hasMany(TripTemplate::class);
    }

    public function reservationsCount()
    {
        $trips = $this->trips()->withCount('reservations')->get();

        return $trips->sum('reservations_count');
    }

    /**
     * Get all the campaign's associated groups.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'campaign_group')
                    ->withPivot(['status_id', 'meta', 'uuid', 'commitment'])
                    ->withTimestamps()
                    ->using(CampaignGroup::class);
    }

    /**
     * Get the campaign's avatar.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function avatar()
    {
        return $this->belongsTo(Upload::class, 'avatar_upload_id');
    }

    /**
     * Get the campaign's banner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function banner()
    {
        return $this->belongsTo(Upload::class, 'banner_upload_id');
    }

    /**
     * Get the campaign's fund.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function fund()
    {
        return $this->morphOne(Fund::class, 'fundable');
    }

    /**
     * Get the campaign's slug.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function slug()
    {
        return $this->morphOne(Slug::class, 'slugable');
    }

    /**
     * Get the campaign's promotionals.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function promotionals()
    {
        return $this->morphMany(Promotional::class, 'promoteable');
    }

    /**
     * Get public campaigns.
     *
     * @param $query
     * @return mixed
     */
    public function scopePublic($query)
    {
        return $query->whereDate('published_at', '<=', date('Y-m-d'));
    }

    /**
     * Get active campaigns.
     *
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->whereDate('ended_at', '>=', date('Y-m-d'));
    }

    /**
     * Get Campaigns by participating organization
     *
     * @param $query
     * @param string $organizationId
     * @return void
     */
    public function scopeOrganization($query, $organizationId)
    {
        return $query->whereHas('groups', function ($group) use ($organizationId) {
            return $group->where('groups.id', $organizationId);
        });
    }

    /**
     * Get all the campaign's trip reservations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function reservations()
    {
        return $this->hasManyThrough(Reservation::class, Trip::class);
    }

    /**
     * Get all the campaign's trip reservations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function interests()
    {
        return $this->hasManyThrough(TripInterest::class, Trip::class);
    }

    /**
     * Get the campaign's links.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function links()
    {
        return $this->morphMany(Link::class, 'linkable');
    }

    /**
     * Get the campaign's uploads.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function uploads()
    {
        return $this->morphToMany(Upload::class, 'uploadable');
    }

    /**
     * Get inactive campaigns.
     *
     * @param $query
     * @return $mixed
     */
    public function scopeInactive($query)
    {
        return $query->where('ended_at', '<', date('Y-m-d'));
    }

    /**
     * Scope a query by id or slug
     * 
     * @param  Builder $query
     * @param  string $param
     * @return Builder
     */
    public function scopeByIdOrSlug($query, $param)
    {
        $query->whereId($param)->orWhereHas('slug', function ($slug) use ($param) {
            return $slug->where('url', $param);
        });
    }

    /**
     * Get the campaign's avatar
     *
     * @return Object
     */
    public function getAvatar()
    {
        if (! $this->avatar) {
            return new Upload([
                'id' => \Ramsey\Uuid\Uuid::uuid4(),
                'name' => 'placeholder',
                'type' => 'avatar',
                'source' => 'images/placeholders/campaign-placeholder.png',
                'meta' => null,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]);
        }

        return $this->avatar;
    }

    public function isPublished()
    {
        return $this->published_at && ! $this->published_at->isFuture();
    }

    /**
     * Update Campaign Slug
     * 
     * @param  string $url
     * @return Campaign
     */
    public function updateSlug($url)
    {
        if ($url) {
            $this->slug()->updateOrCreate(
                [ 'slugable_id' => $this->id, 'slugable_type' => 'campaigns' ],
                [ 'url' => $url ]
            );

            $this->fresh(['slug']);
        }

        return $this;
    }
}
