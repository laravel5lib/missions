<?php

namespace App\Models\v1;

use App\UuidForKey;
use App\Models\v1\Project;
use EloquentFilter\Filterable;
use Laravel\Passport\HasApiTokens;
use App\Notifications\ResetPassword;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Session;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes, Filterable, UuidForKey, CanResetPassword, HasRoles;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'alt_email',
        'phone_one', 'phone_two', 'gender', 'status',
        'birthday', 'address', 'city', 'zip', 'country_code',
        'state', 'timezone', 'url', 'public', 'bio',
        'avatar_upload_id', 'banner_upload_id',
        'created_at', 'updated_at', 'shirt_size', 'height',
        'weight', 'email_token'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'birthday', 'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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


    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Set the user's first name
     *
     * @param $value
     */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = trim(strtolower($value));
    }

    /**
     * Set the user's last name
     *
     * @param $value
     */
    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = trim(strtolower($value));
    }

    /**
     * Get the user's first name
     *
     * @param $value
     * @return string
     */
    public function getFirstNameAttribute($value)
    {
        return ucwords($value);
    }

    /**
     * Get the user's last name
     *
     * @param $value
     * @return string
     */
    public function getLastNameAttribute($value)
    {
        return ucwords($value);
    }

    /**
     * Get the user's full name
     *
     * @param $value
     * @return string
     */
    public function getNameAttribute()
    {
        return ucwords($this->first_name.' '.$this->last_name);
    }

    /**
     * Set the user's email.
     *
     * @param $value
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = trim(strtolower($value));
    }

    /**
     * Set the user's alternate email.
     *
     * @param $value
     */
    public function setAltEmailAttribute($value)
    {
        if ($value) {
            $this->attributes['alt_email'] = trim(strtolower($value));
        }
    }

    /**
     * Set the user's phone one.
     *
     * @param $value
     */
    public function setPhoneOneAttribute($value)
    {
        if ($value) {
            $this->attributes['phone_one'] = trim(stripPhone($value));
        }
    }

    /**
     * Set the user's phone two.
     *
     * @param $value
     */
    public function setPhoneTwoAttribute($value)
    {
        if ($value) {
            $this->attributes['phone_two'] = trim(stripPhone($value));
        }
    }

    /**
     * Get the user's phone one.
     *
     * @param $value
     * @return mixed
     */
    public function getPhoneOneAttribute($value)
    {
        return $value;
    }

    /**
     * Get the user's phone two.
     *
     * @param $value
     * @return mixed
     */
    public function getPhoneTwoAttribute($value)
    {
        return $value;
    }

    /**
     * Set the user's gender.
     *
     * @param $value
     */
    public function setGenderAttribute($value)
    {
        if ($value) {
            $this->attributes['gender'] = trim(strtolower($value));
        }
    }

    /**
     * Get the user's gender.
     *
     * @param $value
     * @return string
     */
    public function getGenderAttribute($value)
    {
        return $value ? strtolower($value) : null;
    }

    /**
     * Set the user's status.
     *
     * @param $value
     */
    public function setStatusAttribute($value)
    {
        if ($value) {
            $this->attributes['status'] = trim(strtolower($value));
        }
    }

    /**
     * Get the user's status.
     *
     * @param $value
     * @return string
     */
    public function getStatusAttribute($value)
    {
        return $value ? strtolower($value) : null;
    }

    /**
     * Set the user's street address.
     *
     * @param $value
     */
    public function setStreetAttribute($value)
    {
        if ($value) {
            $this->attributes['street'] = trim(strtolower($value));
        }
    }

    /**
     * Get the user's street address.
     *
     * @param $value
     * @return string
     */
    public function getStreetAttribute($value)
    {
        return $value ? ucwords($value) : null;
    }

    /**
     * Set the user's city.
     *
     * @param $value
     */
    public function setCityAttribute($value)
    {
        if ($value) {
            $this->attributes['city'] = trim(strtolower($value));
        }
    }

    /**
     * Get the user's city.
     *
     * @param $value
     * @return string
     */
    public function getCityAttribute($value)
    {
        return $value ? ucwords($value) : null;
    }

    /**
     * Set the user's zip code.
     *
     * @param $value
     */
    public function setZipAttribute($value)
    {
        if ($value) {
            $this->attributes['zip'] = trim(strtoupper($value));
        }
    }

    /**
     * Set the user's state.
     *
     * @param $value
     */
    public function setStateAttribute($value)
    {
        if ($value) {
            $this->attributes['state'] = trim(strtolower($value));
        }
    }

    /**
     * Get the user's state.
     *
     * @param $value
     * @return string
     */
    public function getStateAttribute($value)
    {
        return $value ? ucwords($value) : $value;
    }

    /**
     * Set the user's url.
     *
     * @param $value
     */
    public function setUrlAttribute($value)
    {
//        $afterSlash = trim(substr($value, strrpos($value, '/') + 1));
//        $beforePeriod = substr($afterSlash, 0, strpos($afterSlash, '.'));
        if ($value) {
            $this->attributes['url'] = trim(strtolower($value));
        }
    }

    /**
     * Set the user's bio.
     *
     * @param $value
     */
    public function setBioAttribute($value)
    {
        if ($value) {
            $this->attributes['bio'] = trim($value);
        }
    }

    /**
     * Get all the user's reservations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get groups the user has traveled with.
     *
     * @return mixed
     */
    public function getGroups()
    {
        $groupIds = $this->reservations()
                         ->with('trip')
                         ->get()
                         ->pluck('trip.group_id')
                         ->unique();

        $groups = Group::whereIn('id', $groupIds)->public()->get();

        return $groups;
    }

    /**
     * Get all the user's assignments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get all the groups the user manages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function managing()
    {
        return $this->belongsToMany(Group::class, 'managers');
    }

    /**
     * Get all the trips the user facilitates.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function facilitating()
    {
        return $this->belongsToMany(Trip::class, 'facilitators');
    }

    /**
     * Get all the fundraisers the user is sponsoring.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function fundraisers()
    {
        return $this->morphMany(Fundraiser::class, 'sponsor');
    }

    /**
     * Get all the user's passports.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function passports()
    {
        return $this->hasMany(Passport::class);
    }

    /**
     * Get all the user's visas.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function visas()
    {
        return $this->hasMany(Visa::class);
    }

    /**
     * Get all the user's medical releases.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medicalReleases()
    {
        return $this->hasMany(MedicalRelease::class);
    }

    /**
     * Get all the user's referrals.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function referrals()
    {
        return $this->hasMany(Referral::class);
    }

    /**
     * Get all the user's contacts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * Get all the user's notes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Get the user's avatar.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function avatar()
    {
        return $this->belongsTo(Upload::class, 'avatar_upload_id');
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
                'source' => 'images/placeholders/user-placeholder.png',
                'meta' => null,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]);
        }

        return $this->avatar;
    }

    /**
     * Get the user's page banner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function banner()
    {
        return $this->belongsTo(Upload::class, 'banner_upload_id');
    }

    /**
     * Helper method to retrieve the user's banner
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
                'source' => 'images/banners/1n1d17-missionary-2560x800.jpg',
                'meta' => null,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]);
        }

        return $this->banner;
    }

    /**
     * Get the user's uploads.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function uploads()
    {
        return $this->morphToMany(Upload::class, 'uploadable');
    }

    /**
     * Get the user's links.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function links()
    {
        return $this->morphMany(Link::class, 'linkable');
    }

    /**
     * Get all the user's donations made.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function donations()
    {
        return $this->hasMany(Donation::class, 'donor_id');
    }

    /**
     * Get all the user's stories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function stories()
    {
        return $this->morphToMany(Story::class, 'publication', 'published_stories')
            ->withPivot('published_at')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get the all the user's accolades.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function accolades()
    {
        return $this->morphMany(Accolade::class, 'recipient');
    }

    /**
     * Get all the user's essays.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function essays()
    {
        return $this->hasMany(Essay::class);
    }

    /**
     * Get the user's profile slug.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function slug()
    {
        return $this->morphOne(Slug::class, 'slugable');
    }

    /**
     * Get the user's reports.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Get a user that is a rep
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeisRep($query)
    {
        return $query->has('tripsRepresenting')
                     ->orHas('reservationsRepresenting');
    }

    /**
     * Get trips the user is representing
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tripsRepresenting()
    {
        return $this->hasMany(Trip::class, 'rep_id');
    }

    /**
     * Get reservations the user is representing
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservationsRepresenting()
    {
        return $this->hasMany(Reservation::class, 'rep_id');
    }

    /**
     * Synchronize User Profile Links.
     *
     * @param $links
     */
    public function syncLinks($links)
    {
        if (! $links) {
            return;
        }

        $links = collect($links)->reject(function ($link) {
            return strlen($link['url']) < 1;
        })->all();

        $names = $this->links()->pluck('id', 'name');

        foreach ($links as $link) {
            array_forget($names, $link['name']);

            $link['url'] = remove_http($link['url']);

            $this->links()->updateOrCreate(['name' => $link['name']], $link);
        }

        if (! $names->isEmpty()) {
            Link::destroy(array_values($names->toArray()));
        }
    }

    public function getCountriesVisited()
    {
        $this->load('accolades');

        $accolade = $this->accolades()->where('name', 'countries_visited')->first();

        return $accolade->items;
    }

    public function withAvailableRegions()
    {
        $reservations = $this->where('id', $this->id)->with(['reservations' => function ($query) {
            $query->has('membership')->with('membership.team.region.campaign', 'trip');
        }])->first()->reservations;

        $data = collect($reservations)->map(function ($item, $key) {
            return $array = [
                'label'          => $item->trip->started_at->format('Y') .
                                    ' ' . $item->membership->team->region->name .
                                    ' (' . $item->name . ')',
                'team_id'        => $item->membership->team->id,
                'team_call_sign' => $item->membership->team->call_sign,
                'region_id'      => $item->membership->team->region->id,
                'region_name'    => $item->membership->team->region->name,
                'year'           => $item->trip->started_at->format('Y'),
                'campaign'       => $item->membership->team->region->campaign->name,
                'country'        => country($item->membership->team->region->country_code),
                'forms'          => $item->membership->forms,
                'role'           => $item->membership->role_id
            ];
        })->toArray();

        return $data;
        // {campaign year} {region name} (reservation/assignment name)
        // region id
        // region name
        // campaign name
        // campaign year
        // country name
        // team id
        // team call sign
        // role name
        // available forms
    }

    public function setImpersonating($id)
    {
        Session::put('impersonate', $id);
    }

    public function stopImpersonating()
    {
        Session::forget('impersonate');
    }

    public function isImpersonating()
    {
        return Session::has('impersonate');
    }

    /**
     * Determine if the user is the project sponsor.
     *
     * @param  Project $project
     * @return boolean
     */
    public function isSponsor(Project $project)
    {
        if ($project->sponsor_type === 'group') {
            $group_ids = $this->managing()->get('id')->toArray();

            return in_array($project->sponsor_id, $group_ids);
        }

        return $this->id === $project->sponsor_id;
    }

    public function doesManage($resource)
    {
        $class = get_class($resource);

        $resourceIds = (new $class)
            ->managedBy($this->id)
            ->pluck('id')
            ->toArray();

        return in_array($resource->id, $resourceIds);
    }
}
