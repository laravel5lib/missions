<?php

namespace App\Models\v1;

use App\UuidForKey;
use App\Models\v1\User;
use App\Models\v1\Upload;
use App\Models\v1\Reservation;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\InteractsWithReservations;
use Illuminate\Database\Eloquent\SoftDeletes;

class Essay extends Model
{
    use UuidForKey, SoftDeletes, Filterable, InteractsWithReservations;

    protected $guarded = [];

    /**
     * Attributes that should mutated to date instances.
     *
     * @var [type]
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['content' => 'array'];

    /**
     * Set the essay's content attribute
     *
     * @param Array $value
     */
    public function setContentAttribute($value)
    {
        $this->attributes['content'] = json_encode($value);
    }

    /**
     * Get the essay's attached reservations
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get the essay's parent user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the essay's uploads.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function uploads()
    {
        return $this->morphToMany(Upload::class, 'uploadable');
    }
}
