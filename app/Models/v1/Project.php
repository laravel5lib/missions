<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'funded_at',
        'completed_at',
        'launched_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = [
        'project_package_id',
        'rep_id',
        'sponsor_id',
        'sponsor_type',
        'plaque_prefix',
        'plaque_message',
        'funded_at',
        'launched_at',
        'completed_at'
    ];

    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }
}
