<?php

namespace App\Models\v1;

use App\UuidForKey;
use App\Traits\Manageable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\InteractsWithReservations;

class MedicalRelease extends Model
{
    use UuidForKey, InteractsWithReservations, Manageable;

    /**
     * Attributes that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'ins_provider', 'ins_policy_no',
        'is_risk', 'name', 'emergency_contact',
        'created_at', 'updated_at', 'takes_medication',
        'height', 'weight', 'pregnant'
    ];

    /**
     * Attributes that should be cast as date objects.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];

    /**
     * Attributes to be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'emergency_contact' => 'array',
        'takes_medication' => 'boolean',
        'pregnant' => 'boolean'
    ];

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
     * The notes associated with the release.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphManyclear
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
        return $this->hasMany(MedicalCondition::class)->orderBy('name');
    }

    /**
     * Medical allergies listed on the release.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allergies()
    {
        return $this->hasMany(MedicalAllergy::class)->orderBy('name');
    }

    public function getHeightStandardAttribute()
    {
        $inches = $this->height/2.54;
        $feet = intval($inches/12);
        $inches = $inches%12;
        
        return sprintf('%d\' %d"', $feet, $inches);
    }

    public function getWeightStandardAttribute()
    {
        return round($this->weight * 2.20462);
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
        if (! $conditions) {
            return $this->conditions()->delete();
        }

        $names = $this->conditions()->pluck('name', 'name');

        foreach ($conditions as $condition) {
            array_forget($names, $condition['name']);

            $this->conditions()->updateOrCreate(['name' => $condition['name']], $condition);
        }

        if (! $names->isEmpty()) {
            $this->conditions()
                                    ->whereIn('name', $names)
                                    ->delete();
        }
    }

    /**
     * Synchronize medical allergies.
     *
     * @param array $allergies
     */
    public function syncAllergies($allergies)
    {
        if (! $allergies) {
            return $this->allergies()->delete();
        }

        $names = $this->allergies()->pluck('name', 'name');

        foreach ($allergies as $allergy) {
            array_forget($names, $allergy['name']);

            $this->allergies()->updateOrCreate(['name' => $allergy['name']], $allergy);
        }

        if (! $names->isEmpty()) {
            $this->allergies()
            ->whereIn('name', $names)
            ->delete();
        }
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
