<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requirement extends Model
{
    use Filterable, UuidForKey, SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'requirements';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'short_desc', 'document_type', 'requester_id', 'requester_type', 'due_at'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at', 'due_at'
    ];

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
    public $timestamps = true;

    /**
     * Get all of the owning requirable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function requester()
    {
        return $this->morphTo();
    }

    /**
     * Get all the requirement's reservations
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'reservation_requirements')
                    ->withPivot('grace_period', 'status', 'document_type', 'id')
                    ->withTrashed()
                    ->withTimestamps();
    }

    /**
     * Get the requirement's conditions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function conditions()
    {
        return $this->hasMany(RequirementCondition::class);
    }

    /**
     * Scope query to requirements belonging to a campaign.
     * 
     * @param  Illuminate\Database\Eloquent\Builder $query 
     * @param  String $id
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeCampaignId($query, $id)
    {
        return $query->whereRequesterType('campaigns')->whereRequesterId($id);
    }
}
