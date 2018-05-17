<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use Filterable, UuidForKey;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'todos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task', 'completed_at', 'user_id', 'todoable_id', 'todoable_type'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['completed_at'];

    /**
     * All of the relationships to be touched.
     * Update the parent's timestamp.
     *
     * @var array
     */
    protected $touches = [];

    /**
     * Indicates if the model should be timestamped
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Set the todos task
     *
     * @param $value
     */
    public function setTaskAttribute($value)
    {
        $this->attributes['task'] = trim(strtolower($value));
    }

    /**
     * Get all the owning todoable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function todoable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user who completed the task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
