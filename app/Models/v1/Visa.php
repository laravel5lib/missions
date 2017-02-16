<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visa extends Model
{
    use SoftDeletes, Filterable, UuidForKey;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'visas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'given_names', 'surname', 'number', 'country_code',
        'upload_id', 'issued_at', 'expires_at', 'user_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['issued_at', 'expires_at', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * All of the relationships to be touched.
     * Update the parent's timestamp.
     *
     * @var array
     */
    protected $touches = ['user'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The the visa's owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the visa's photo copy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function upload()
    {
        return $this->belongsTo(Upload::class);
    }

    /**
     * Get all visa's reservations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Set the visa's given names.
     *
     * @param $value
     */
    public function setGivenNamesAttribute($value)
    {
        $this->attributes['given_names'] = trim(strtolower($value));
    }

    /**
     * Get the visa's given names.
     *
     * @param $value
     * @return string
     */
    public function getGivenNamesAttribute($value)
    {
        return ucwords($value);
    }

    /**
     * Set the visa's surname.
     *
     * @param $value
     */
    public function setSurnameAttribute($value)
    {
        $this->attributes['surname'] = trim(strtolower($value));
    }

    /**
     * Get the visa's surname.
     *
     * @param $value
     * @return string
     */
    public function getSurnameAttribute($value)
    {
        return ucwords($value);
    }

    /**
     * Set the visa's number.
     *
     * @param $value
     */
    public function setNumberAttribute($value)
    {
        $this->attributes['number'] = trim(strtoupper($value));
    }

    /**
     * Set the visa's scan source.
     *
     * @param $value
     */
    public function setScanSrcAttribute($value)
    {
        $this->attributes['scan_src'] = trim($value);
    }
}
