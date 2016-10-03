<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cause extends Model
{
    use SoftDeletes;

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
        'upload_id',
        'active'
    ];

    /**
     * Get the initiatives for the cause.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function initiatives()
    {
        return $this->hasMany(ProjectInitiative::class);
    }

    /**
     * Get the causes' image.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function image()
    {
        return $this->belongsTo(Upload::class, 'upload_id');
    }

}
