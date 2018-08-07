<?php

namespace App\Models\v1;

use App\UuidForKey;
use App\Models\v1\Team;
use App\Scopes\PublicScope;
use App\Traits\Promoteable;
use Conner\Tagging\Taggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes, Filterable, UuidForKey, Taggable, Promoteable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type', 'timezone', 'public',
        'address_one', 'address_two',
        'city', 'state', 'zip', 'country_code', 'phone_one',
        'phone_two', 'email', 'description',
        'stripe_id', 'card_brand', 'card_last_four',
        'status', 'avatar_upload_id', 'banner_upload_id',
        'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * All of the relationships to be touched.
     * Update the parent's timestamp.
     *
     * @var array
     */
    protected $touches = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['public' => 'boolean'];

    /**
     * Set default values.
     *
     * @var array
     */
    protected $attributes = ['status' => 'approved'];

    protected $appends = ['url', 'avatar_url', 'banner_url'];

    public function getUrlAttribute()
    {
        return $this->slug ? $this->slug->url : null;
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? image($this->avatar->source) : url('/images/placeholders/logo-placeholder.png');
    }

    public function getBannerUrlAttribute()
    {
        return $this->banner ? image($this->banner->source) : null;
    }

    /**
     * Set the status attribute.
     *
     * @param $value
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = strtolower(trim($value));
    }

    /**
     * Get all the group's trips.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function teams()
    {
        return $this->morphToMany(Team::class, 'teamable');
    }

    /**
     * Get all the group's trip reservations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function reservations()
    {
        return $this->hasManyThrough(Reservation::class, Trip::class);
    }

    /**
     * Get all the group's active trip reservations.
     *
     * @return Response
     */
    public function activeReservations()
    {
        return $this->reservations()->whereHas('trip', function ($trip) {
            return $trip->active();
        });
    }

    /**
     * Get all the group's managers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function managers()
    {
        return $this->belongsToMany(User::class, 'managers')
                    ->withTimestamps();
    }

    /**
     * Get all the group's trip facilitators.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function facilitators()
    {
        return $this->hasManyThrough(Facilitator::class, Trip::class);
    }

    /**
     * Get all the group's fundraisers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function fundraisers()
    {
        return $this->morphMany(Fundraiser::class, 'sponsor');
    }

    /**
     * Get all of the group's tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Get the group's avatar.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function avatar()
    {
        return $this->belongsTo(Upload::class, 'avatar_upload_id');
    }

    /**
     * Get the group's page banner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function banner()
    {
        return $this->belongsTo(Upload::class, 'banner_upload_id');
    }

    /**
     * Get the group's promotionals.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function promotionals()
    {
        return $this->morphMany(Promotional::class, 'promoteable');
    }

    /**
     * Helper method to retrieve the user's avatar
     *
     * @return mixed
     */
    public function getAvatar()
    {
        if (! $this->avatar) {
            return new Upload([
                'id' => \Ramsey\Uuid\Uuid::uuid4(),
                'name' => 'placeholder',
                'type' => 'avatar',
                'source' => 'images/placeholders/logo-placeholder.png',
                'meta' => null,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]);
        }

        return $this->avatar;
    }

    /**
     * Helper method to retrieve the group's banner
     *
     * @return mixed
     */
    public function getBanner()
    {
        if (! $this->banner) {
            return new Upload([
                'id' => \Ramsey\Uuid\Uuid::uuid4(),
                'name' => 'default',
                'type' => 'banner',
                'source' => 'images/banners/1n1d17-vision3-2560x800.jpg',
                'meta' => null,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]);
        }

        return $this->banner;
    }

    /**
     * Get the group's social links.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function social()
    {
        return $this->morphMany(Link::class, 'linkable');
    }

    /**
     * Get the group's uploads.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function uploads()
    {
        return $this->morphedByMany(Upload::class, 'uploadable');
    }

    /**
     * Get the group's stories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function stories()
    {
        return $this->morphToMany(Story::class, 'publication', 'published_stories')
            ->withPivot('published_at')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get the group's notes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    /**
     * Get the group's slug.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function slug()
    {
        return $this->morphOne(Slug::class, 'slugable');
    }

    /**
     * Get the group's links.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function links()
    {
        return $this->morphMany(Link::class, 'linkable');
    }

    /**
     * Get public groups.
     *
     * @param $query
     * @return mixed
     */
    public function scopePublic($query)
    {
        return $query->where('public', true);
    }

    /**
     * Get private groups.
     *
     * @param $query
     * @return mixed
     */
    public function scopePrivate($query)
    {
        return $query->where('public', false);
    }

    /**
     * Synchronize all the group's managers.
     *
     * @param $user_ids
     */
    public function syncManagers($user_ids = null)
    {
        if (is_null($user_ids)) {
            return;
        }

        $this->managers()->sync($user_ids);
    }
}
