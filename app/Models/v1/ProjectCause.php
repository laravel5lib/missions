<?php

namespace App\Models\v1;

use App\UuidForKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectCause extends Model
{
    use SoftDeletes, UuidForKey;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that can be mass assigned
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'short_desc',
        'upload_id',
        'countries'
    ];

    /**
     * Attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'countries' => 'array'
    ];

    /**
     * Set the countries attribute.
     *
     * @param $value
     * @return string
     */
    public function setCountriesAttribute($value)
    {
        return $this->attributes['countries'] = json_encode($value);
    }

    /**
     * Get the project types for the cause.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function initiatives()
    {
        return $this->hasMany(ProjectInitiative::class);
    }

    /**
     * Get all the projects for the cause.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function projects()
    {
        return $this->hasManyThrough(Project::class, ProjectInitiative::class);
    }

    /**
     * Get the causes' image.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function image()
    {
        return $this->belongsTo(Upload::class, 'upload_id');
    }

    /**
     * Get the project causes' countries.
     *
     * @return array
     */
    public function getCountries()
    {
        $countries = collect($this->countries)->map(function ($country) {
            return  [
               'code' => $country,
               'name' => country($country)
            ];
        });

        return $countries->all();
    }

    /**
     * Get the cause's fund.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function fund()
    {
        return $this->morphOne(Fund::class, 'fundable');
    }
}
