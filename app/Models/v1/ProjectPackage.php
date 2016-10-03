<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Model;

class ProjectPackage extends Model
{
    public $timestamps = false;

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['initiative', 'type', 'enhancements'];

    /**
     * The attributes that can be mass assigned
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'currency_code',
        'generate_dates',
        'project_type_id',
        'project_initiative_id'
    ];

    /**
     * The initiative belonging to the package.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function initiative()
    {
        return $this->belongsTo(ProjectInitiative::class);
    }

    /**
     * The project type belonging to the package.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(ProjectType::class);
    }

    /**
     * The dates for this package.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function deadlines()
    {
        return $this->morphMany(Deadline::class, 'deadline_assignable');
    }

    /**
     * The costs for this package.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function costs()
    {
        return $this->morphMany(Cost::class, 'cost_assignable');
    }

    /**
     * Get the projects with this package.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
