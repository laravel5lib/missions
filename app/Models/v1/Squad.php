<?php

namespace App\Models\v1;

use Ramsey\Uuid\Uuid;
use App\Models\v1\Region;
use App\Models\v1\SquadMember;
use App\Notifications\SquadPublished;
use Illuminate\Database\Eloquent\Model;

class Squad extends Model
{
    /**
     * Boot the Uuid trait for the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Uuid::uuid4();
        });
    }
    
    /**
     * Attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['callsign', 'region_id', 'published'];

    /**
     * Attributes that are cast to native date types.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Attributes that are cast to native types.
     *
     * @var array
     */
    protected $casts = ['published' => 'boolean'];

    /**
     * The region the squad belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * The members belonging to the squad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function members()
    {
        return $this->hasMany(SquadMember::class);
    }

    /**
     * Move existing members to this squad or create new members.
     *
     * @param array|string $ids
     * @param string $group
     * @return void
     */
    public function updateOrAddMembers($ids, $group = null)
    {
        collect($ids)->each( function ($id) use ($group) {
            $member = SquadMember::updateOrCreate(
                [ 'reservation_id' => $id ],
                [ 'group' => $group, 'squad_id' => $this->id ]
            );

            if ($member->squad->published) {
                $member->reservation->notify(new SquadPublished($member->squad));
            }
        });
    }

    /**
     * Add members to the squad.
     *
     * @param array|string $ids
     * @param string $group
     * @return void
     */
    public function addMembers($ids, $group = null)
    {
        $members = collect($ids)->map( function ($id) use ($group) {
            return new SquadMember(
                ['reservation_id' => $id, 'group' => $group]
            ); 
        })->all();

        return $this->members()->saveMany($members);
    }

    /**
     * Scope query to regions with the campaign id.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $campaignId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCampaignId($query, $campaignId)
    {
        return $query->whereHas('region', function ($subQuery) use ($campaignId) {
            return $subQuery->where('campaign_id', $campaignId);
        });
    }
}
