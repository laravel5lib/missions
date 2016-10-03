<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectInitiative extends Model
{
    use SoftDeletes;

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['cause', 'types'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'started_at',
        'ended_at',
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
        'cause_id',
        'country_code',
        'started_at',
        'ended_at',
        'active',
        'rep_id'
    ];

    /**
     * The Cause the Initiative belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cause()
    {
        return $this->belongsTo(Cause::class);
    }

    /**
     * The rep assigned to this initiative
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rep()
    {
        return $this->belongsTo(User::class, 'rep_id');
    }

    /**
     * The active packages associated with the initiative
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function packages()
    {
        return $this->hasMany(ProjectPackage::class)->whereHas('type', function($type) {
            $type->whereActive(true);
        });
    }
}
