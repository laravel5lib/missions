<?php

namespace App\Models\v1\Medical;

use App\Models\v1\Note;
use App\Models\v1\Reservation;
use App\Models\v1\User;
use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Release extends Model
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
        'user_id', 'ins_provider', 'ins_policy_no', 'conditions',
        'allergies', 'is_risk', 'name', 'emergency_contact'
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
        'conditions' => 'array',
        'allergies' => 'array',
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
     * Set the conditions.
     *
     * @param $value
     */
    public function setConditionsAttribute($value)
    {
        $this->attributes['conditions'] = json_encode($value);
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

    /**
     * Set the allergies.
     *
     * @param $value
     */
    public function setAllergiesAttribute($value)
    {
        $this->attributes['allergies'] = json_encode($value);
    }

}
