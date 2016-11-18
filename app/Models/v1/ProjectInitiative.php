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
     * The attributes that can be mass assigned
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'short_desc',
        'country_code',
        'upload_id',
        'started_at',
        'ended_at'
    ];

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
     * Get the project initiative's costs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function costs()
    {
        return $this->morphMany(Cost::class, 'cost_assignable');
    }

    /**
     * Get the project initiative's deadlines.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function deadlines()
    {
        return $this->morphMany(Deadline::class, 'deadline_assignable');
    }

    /**
     * Get all the project initiative's active costs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activeCosts()
    {
        return $this->costs()->active();
    }

    /**
     * Get current initiatives.
     *
     * @param $query
     * @return mixed
     */
    public function scopeCurrent($query)
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
