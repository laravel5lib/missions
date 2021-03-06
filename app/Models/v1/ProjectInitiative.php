<?php

namespace App\Models\v1;

use App\UuidForKey;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use EloquentFilter\Filterable;

class ProjectInitiative extends Model
{
    use Filterable, SoftDeletes, UuidForKey;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'started_at',
        'ended_at'
    ];

    /**
     * The attributes that should not be mass assigned
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the project initiative's image.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function image()
    {
        return $this->belongsTo(Upload::class, 'upload_id');
    }

    /**
     * Get the project initiative's cause.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cause()
    {
        return $this->belongsTo(ProjectCause::class, 'project_cause_id');
    }

    /**
     * Get all the initiative's projects.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get the initiative's deadlines.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function deadlines()
    {
        return $this->morphMany(Deadline::class, 'deadline_assignable');
    }

    /**
     * Get new initiatives.
     *
     * @param $query
     * @return mixed
     */
    public function scopeNew($query)
    {
        return $query->where('ended_at', '>=', Carbon::now());
    }

    /**
     * Get past initiatives.
     *
     * @param $query
     * @return mixed
     */
    public function scopePast($query)
    {
        return $query->where('ended_at', '<', Carbon::now());
    }
}
