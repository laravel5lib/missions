<?php

namespace App\Models\v1;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\v1\Squad;
use App\Models\v1\Reservation;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Model;

class SquadMember extends Model
{
    /**
     * Attributes that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = ['reservation_id', 'squad_id', 'group'];

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
     * The reservation the member belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * The squad the member belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function squad()
    {
        return $this->belongsTo(Squad::class);
    }

    /**
     * Scope query to only include members with the squad id.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $uuid
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSquadId($query, $uuid)
    {
        return $query->whereHas('squad', function ($subQuery) use ($uuid) {
            return $subQuery->whereUuid($uuid);
        });
    }

    public function scopeCampaignId($query, $id)
    {
        return $query->where('regions.campaign_id', $id);
    }

    public function scopeCampaign($query, $id)
    {
        return $query->whereHas('squad.region', function ($subQuery) use ($id) {
            return $subQuery->where('campaign_id', $id);
        });
    }

    public function scopeSquadCallsign($query, $callsign)
    {
        return $query->whereRaw("squads.callsign LIKE '%$callsign%'");
    }

    public function scopeRole($query, $role)
    {
        return $query->where('desired_role', $role);
    }

    public function scopeTripType($query, $type)
    {
        return $query->where('trips.type', $type);
    }

    public function scopeOrganizationName($query, $name)
    {
        return $query->where('groups.name', 'LIKE', "%$name%");
    }

    public function scopeOrganizationId($query, $id)
    {
        return $query->where('groups.id', $id);
    }

    public function scopeAgeRange($query, ...$ages)
    {
        if(isset($ages[1])) {
            $startDob = Carbon::now()->startOfYear()->subYears($ages[1]);
            $endDob = Carbon::now()->startOfYear()->subYears($ages[0]);

            $query->whereBetween('reservations.birthday', [$startDob, $endDob]);
        } else {
            $dob = Carbon::now()->startOfYear()->subYears($ages[0]);

            $query->where('reservations.birthday', '<=', $dob);
        }
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('reservations.status', $status);
    }

    /**
     * Build the query string to get squad members.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function buildQuery()
    {
        $joins = static::leftJoin('squads', 'squads.id', '=', 'squad_members.squad_id')
            ->leftJoin('regions', 'regions.id', '=', 'squads.region_id')
            ->leftJoin('reservations', 'reservations.id', '=', 'squad_members.reservation_id')
            ->leftJoin('trips', 'trips.id', '=', 'reservations.trip_id')
            ->leftJoin('groups', 'groups.id', '=', 'trips.group_id');

        $query = QueryBuilder::for($joins)->allowedFilters([
            'surname', 'given_names', 'group',
            Filter::exact('region_id'),
            Filter::exact('gender'),
            Filter::scope('status'),
            Filter::scope('campaign_id'),
            Filter::scope('organization_name'),
            Filter::scope('organization_id'),
            Filter::scope('trip_type'),
            Filter::scope('role'),
            Filter::scope('squad_id'),
            Filter::scope('squad_callsign'),
            Filter::scope('age_range')
        ])->select([
            'squad_members.id', 
            'squad_members.uuid', 
            'squad_members.squad_id', 
            'squad_members.group',
            'squad_members.created_at',
            'squad_members.updated_at',
            'squads.callsign',
            'squads.region_id',
            'reservations.id as reservation_id',
            'reservations.surname', 
            'reservations.given_names', 
            'reservations.gender',
            'reservations.status',
            'reservations.birthday',
            'reservations.desired_role as role',
            'regions.id',
            'regions.campaign_id',
            'regions.name as region_name',
            'trips.type as trip_type',
            'groups.name as organization_name'
        ]);

        return $query;
    }
}
