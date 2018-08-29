<?php

namespace App\Models\v1;

use App\UuidForKey;
use App\Models\v1\Trip;
use App\RequirementRules;
use EloquentFilter\Filterable;
use App\Models\v1\CampaignGroup;
use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    use Filterable, UuidForKey;
    
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
    protected $fillable = ['name', 'short_desc', 'document_type', 'requester_id', 'requester_type', 'due_at', 'rules'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at', 'due_at'
    ];

    protected $casts = ['rules' => 'array'];

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
     * Get all the groups assigned to this requirement.
     * 
     * @return Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function groups()
    {
        return $this->morphedByMany(CampaignGroup::class, 'requireable', 'requireables', 'requirement_id', 'requireable_id', 'id', 'uuid');
    }

    /**
     * Get all the groups assigned to this requirement.
     * 
     * @return Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function trips()
    {
        return $this->morphedByMany(Trip::class, 'requireable');
    }

    /**
     * Get all the requirement's reservations
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function reservations()
    {
        return $this->morphedByMany(Reservation::class, 'requireable');
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

    public function isCustom($type, $id)
    {
        return $this->requester_id === $id 
            && $this->requester_type === $type;
    }
}
