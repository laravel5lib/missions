<?php

namespace App\Models\v1;

use App\UuidForKey;
use App\Traits\Manageable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\InteractsWithReservations;

class Visa extends Model
{
    use UuidForKey, InteractsWithReservations, Manageable;

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
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['issued_at', 'expires_at', 'created_at', 'updated_at'];

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
     * Set the visa's number.
     *
     * @param $value
     */
    public function setNumberAttribute($value)
    {
        $this->attributes['number'] = strtoupper($value);
    }
}
