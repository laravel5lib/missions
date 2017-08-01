<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class MedicalAllergy extends Model
{
    use Filterable, UuidForKey;

    /**
     * Available allergies
     *
     * @var array
     */
    protected static $allergies = [
        'Peanuts',
        'Milk',
        'Eggs',
        'Wheat',
        'Fruit',
        'Nuts',
        'Fish',
        'Pollen',
        'Dust Mites',
        'Mold',
        'Animal Dander',
        'Insect Sting',
        'Latex',
        'Certain Drugs'
    ];

    /**
     * Table used by the model.
     *
     * @var string
     */
    protected $table = 'medical_allergies';

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
     * Get all the available allergies.
     *
     * @return array
     */
    public static function available()
    {
        return static::$allergies;
    }

    /**
     * Return a comma separated string of allergies
     *
     * @return string
     */
    public static function pluck()
    {

        return implode(',', array_flatten(static::available()));
    }

    /**
     * Get the medical release the allergy belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function medicalRelease()
    {
        return $this->belongsTo(MedicalRelease::class);
    }

    /**
     * Get the medical allergies's attachments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function attachments()
    {
        return $this->morphToMany(Upload::class, 'uploadable');
    }
}
