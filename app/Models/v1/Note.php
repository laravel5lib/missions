<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use SoftDeletes, Filterable, UuidForKey;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject', 'content', 'user_id', 'noteable_id', 'noteable_type', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

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
     * Set the note's subject
     *
     * @param $value
     */
    public function setSubjectAttribute($value)
    {
        $this->attributes['subject'] = trim($value);
    }

    /**
     * Set the note's content
     *
     * @param $value
     */
    public function setContentAttribute($value)
    {
        $this->attributes['content'] = trim($value);
    }

    /**
     * Get the note's subject
     *
     * @param $value
     * @return string
     */
    public function getSubjectAttribute($value)
    {
        return ucwords($value);
    }

    /**
     * Get the note's content
     *
     * @param $value
     * @return string
     */
    public function getContentAttribute($value)
    {
        return $value;
    }

    /**
     * Get all of the owning noteable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function noteable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user who owns the note.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get most recent notes.
     *
     * @param $query
     * @param int $number
     * @return mixed
     */
    public function scopeRecent($query, $number = 3)
    {
        return $query->latest()->limit($number);
    }
}
