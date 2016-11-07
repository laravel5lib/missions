<?php

namespace App\Models\v1;

use App\UuidForKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectType extends Model
{
    use SoftDeletes, UuidForKey;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that can be mass assigned
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'short_desc',
        'country_code',
        'upload_id'
    ];

    /**
     * Get the project type's image.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function image()
    {
        return $this->belongsTo(Upload::class, 'upload_id');
    }

    /**
     * Get the project type's cause.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cause()
    {
        return $this->belongsTo(ProjectCause::class);
    }

    /**
     * Get all the type's projects.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get the project type's costs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function costs()
    {
        return $this->morphMany(Cost::class, 'cost_assignable');
    }

    /**
     * Get all the project type's active costs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activeCosts()
    {
        return $this->costs()->active();
    }
}
