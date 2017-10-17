<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class MedicalCondition extends Model
{
    use Filterable, UuidForKey;

    /**
     * Available conditions.
     *
     * @var array
     */
    protected static $conditions = [
        'Amoebic Dysentery',
        'Anxiety',
        'Asthma',
        'Back Pain',
        'Depression',
        'Diabetes',
        'Epilepsy',
        'Fever',
        'Foot/Leg Difficulties',
        'Gastro Intestinal',
        'Heart',
        'Hepatitis',
        'HIV/AIDS',
        'Hypertension',
        'Hypoclycemia',
        'Infectious Mononucleosis',
        'Kidney Trouble',
        'Malaria',
        'Mental Disorder',
        'Migraine Headache',
        'Muscle Pain',
        'Paralysis',
        'Pneumonia',
        'Pregnancy',
        'Rheumatic',
        'Sleep Disorder',
        'Tuberculosis',
        'Ulcers'
    ];

    /**
     * Table used by the model.
     *
     * @var string
     */
    protected $table = 'medical_conditions';

    /**
     * Attributes that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'medication', 'diagnosed'
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
     * Attributes to be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'medication' => 'boolean',
        'diagnosed' => 'boolean'
    ];

    /**
     * Enable timestamps.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The parent model of the timestamps to be updated.
     *
     * @var array
     */
    protected $touches = ['medicalRelease'];

    /**
     * Return an array of all available conditions.
     *
     * @return array
     */
    public static function available()
    {
        return static::$conditions;
    }

    /**
     * Return a comma separated string of available conditions.
     *
     * @return string
     */
    public static function pluck()
    {
        return implode(',', array_flatten(static::available()));
    }

    /**
     * Get the medical release the condition belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function medicalRelease()
    {
        return $this->belongsTo(MedicalRelease::class);
    }

    /**
     * Get the medical conditions's attachments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function attachments()
    {
        return $this->morphToMany(Upload::class, 'uploadable');
    }
}
