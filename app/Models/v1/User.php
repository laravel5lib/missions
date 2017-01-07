<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Support\Facades\Session;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use SoftDeletes, Filterable, UuidForKey, HasRolesAndAbilities, CanResetPassword;

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
        'name', 'email', 'password', 'alt_email',
        'phone_one', 'phone_two', 'gender', 'status',
        'birthday', 'address', 'city', 'zip', 'country_code',
        'state', 'timezone', 'url', 'public', 'bio',
        'stripe_id', 'card_brand', 'card_last_four',
        'avatar_upload_id', 'banner_upload_id'
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
     * Get the identifier that will be stored in the subject claim of the JWT
     *
     * @return mixed
     */
    public function getJWTIdentifier(){
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT
     *
     * @return array
     */
    public function getJWTCustomClaims(){
        return [];
    }

    /**
     * Set the user's password
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Set the user's name
     *
     * @param $value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(strtolower($value));
    }

    /**
     * Get the user's name
     *
     * @param $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        return ucfirst($value);
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
        if ($value)
            $this->attributes['alt_email'] = trim(strtolower($value));
    }

    /**
     * Set the user's phone one.
     *
     * @param $value
     */
    public function setPhoneOneAttribute($value)
    {
        if ($value)
            $this->attributes['phone_one'] = trim(stripPhone($value));
    }

    /**
     * Set the user's phone two.
     *
     * @param $value
     */
    public function setPhoneTwoAttribute($value)
    {
        if ($value)
            $this->attributes['phone_two'] = trim(stripPhone($value));
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
        if ($value)
            $this->attributes['gender'] = trim(strtolower($value));
    }

    /**
     * Get the user's gender.
     *
     * @param $value
     * @return string
     */
    public function getGenderAttribute($value)
    {
        return $value ? ucfirst($value) : null;
    }

    /**
     * Set the user's status.
     *
     * @param $value
     */
    public function setStatusAttribute($value)
    {
        if ($value)
            $this->attributes['status'] = trim(strtolower($value));
    }

    /**
     * Get the user's status.
     *
     * @param $value
     * @return string
     */
    public function getStatusAttribute($value)
    {
        return $value ? ucfirst($value) : null;
    }

    /**
     * Set the user's street address.
     *
     * @param $value
     */
    public function setStreetAttribute($value)
    {
        if ($value)
            $this->attributes['street'] = trim(strtolower($value));
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
        if ($value)
            $this->attributes['city'] = trim(strtolower($value));
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
        if ($value)
            $this->attributes['zip'] = trim(strtoupper($value));
    }

    /**
     * Set the user's state.
     *
     * @param $value
     */
    public function setStateAttribute($value)
    {
        if ($value)
            $this->attributes['state'] = trim(strtolower($value));
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
        if ($value)
            $this->attributes['url'] = trim(strtolower($value));
    }

    /**
     * Set the user's bio.
     *
     * @param $value
     */
    public function setBioAttribute($value)
    {
        if ($value)
            $this->attributes['bio'] = trim($value);
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
     * Get the user's page banner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function banner()
    {
        return $this->belongsTo(Upload::class, 'banner_upload_id');
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
     * Synchronize User Profile Links.
     *
     * @param $links
     */
    public function syncLinks($links)
    {
        if ( ! $links) return;

        $links = collect($links)->reject(function($link) {
            return strlen($link['url']) < 1;
        })->all();

        $names = $this->links()->pluck('id', 'name');

        foreach($links as $link)
        {
            array_forget($names, $link['name']);

            $link['url'] = remove_http($link['url']);

            $this->links()->updateOrCreate(['name' => $link['name']], $link);
        }

        if( ! $names->isEmpty()) Link::destroy(array_values($names->toArray()));
    }

    public function getCountriesVisited()
    {
        $this->load('accolades');

        $accolade = $this->accolades()->where('name', 'countries_visited')->first();

        return $accolade->items;
    }

    public function upcomingPayments()
    {
        $dues = Due::whereIn(
            'payable_id',
            $this->reservations()->pluck('id')->toArray()
        )->where('payable_type', 'reservations')
         ->withBalance()
         ->sortRecent()
         ->take(5)
         ->get();

        return $dues;
    }

    public function outstandingRequirements()
    {
        $requirements = $this->reservations()
             ->with('requirements')
             ->get()
             ->pluck('requirements')
             ->flatten()
             ->reject(function($item) {
                 $item->status = 'incomplete';
             })
             ->sortBy('due_at')
             ->take(5);

        return $requirements;
    }

    public function recentDonations()
    {
        $donations = $this->reservations()
            ->with('fund.donations')
            ->get()
            ->pluck('donations')
            ->flatten()
            ->sortBy('created_at')
            ->take(5);


        return $donations;
    }

    public function withAvailableRegions()
    {
        $reservations = $this->where('id', $this->id)->with(['reservations' => function ($query) {
            $query->has('membership')->with('membership.team.region.campaign', 'trip');
        }])->first()->reservations;

        $data = collect($reservations)->map(function ($item, $key)
        {
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
}
