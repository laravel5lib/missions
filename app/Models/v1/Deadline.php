<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Deadline extends Model
{
    use Filterable, UuidForKey;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'deadlines';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'date', 'grace_period', 'enforced'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date'];

    /**
     * All of the relationships to be touched.
     * Update the parent's timestamp.
     *
     * @var array
     */
    protected $touches = ['reservations'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get all of the owning deadline assignable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function deadlineAssignable()
    {
        return $this->morphTo('deadline_assignable');
    }

    /**
     * Get all the reservations with the deadline.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'reservation_deadlines')
                    ->withPivot('grace_period')
                    ->withTimestamps();
    }

    /**
     * Get all of the deadline's tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
