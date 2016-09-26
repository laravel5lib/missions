<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class MedicalRelease extends Model
{
    use Filterable, UuidForKey;

    /**
     * Table used by the model.
     *
     * @var string
     */
    protected $table = 'medical_releases';

    /**
     * Attributes that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'ins_provider', 'ins_policy_no',
        'is_risk', 'name', 'emergency_contact'
    ];

    /**
     * Attributes that should be hidden from JSON output.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Attributes that should be cast as date objects.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];

    /**
     * The parent model of the timestamps to be updated.
     *
     * @var array
     */
    protected $touches = ['user'];

    /**
     * Attributes to be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'emergency_contact' => 'array'
    ];

    /**
     * Enable timestamps.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The user the release belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The reservations associated with the release.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * The notes associated with the release.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    /**
     * Medical conditions listed on the release.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function conditions()
    {
        return $this->hasMany(MedicalCondition::class);
    }

    /**
     * Medical allergies listed on the release.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allergies()
    {
        return $this->hasMany(MedicalAllergy::class);
    }

    /**
     * Set the emergency contact.
     *
     * @param $value
     */
    public function setEmergencyContactAttribute($value)
    {
        $this->attributes['emergency_contact'] = json_encode($value);
    }

}
