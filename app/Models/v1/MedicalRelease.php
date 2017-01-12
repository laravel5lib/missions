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

    /**
     * Synchronize medical conditions.
     *
     * @param array $conditions
     */
    public function syncConditions($conditions)
    {
        if ( ! $conditions) return $this->conditions()->delete();

        $names = $this->conditions()->lists('name', 'name');

        foreach($conditions as $condition)
        {
            array_forget($names, $condition['name']);

            $this->conditions()->updateOrCreate(['name' => $condition['name']], $condition);
        }

        if( ! $names->isEmpty()) $this->conditions()
                                    ->whereIn('name', $names)
                                    ->delete();
    }

    /**
     * Synchronize medical allergies.
     *
     * @param array $allergies
     */
    public function syncAllergies($allergies)
    {
        if ( ! $allergies) return $this->allergies()->delete();

        $names = $this->allergies()->lists('name', 'name');

        foreach($allergies as $allergy)
        {
            array_forget($names, $allergy['name']);

            $this->allergies()->updateOrCreate(['name' => $allergy['name']], $allergy);
        }

        if( ! $names->isEmpty()) $this->allergies()
            ->whereIn('name', $names)
            ->delete();
    }

    /**
     * Get the medical release's uploads.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function uploads()
    {
        return $this->morphToMany(Upload::class, 'uploadable');
    }


}
