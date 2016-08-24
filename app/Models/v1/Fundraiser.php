<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fundraiser extends Model
{
    use Filterable, UuidForKey;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fundraisers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'started_at', 'ended_at', 'goal_amount', 'description',
        'sponsor_id', 'sponsor_type', 'url', 'type'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'started_at', 'ended_at', 'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the fundraiser's sponsor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function sponsor()
    {
        return $this->morphTo();
    }

    /**
     * Get all of the owning fundable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function fundable()
    {
        return $this->morphTo();
    }

    public function donations()
    {
        return $this->fundable->donations();
    }

    public function countDonors()
    {
        $filters = [
            str_singular($this->fundable_type) => $this->fundable_id,
            'starts'                           => $this->started_at,
            'ends'                             => $this->ended_at
        ];

        return Donor::filter($filters)->count();
    }

    /**
     * Get the fundraiser's total amount raised.
     *
     * @return mixed
     */
    public function raised()
    {
        return $this->donations()
            ->whereBetween('created_at', [
                $this->started_at, $this->ended_at
            ])
            ->sum('amount');
    }

    /**
     * Get the fundraiser's page banner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function banner()
    {
        return $this->belongsTo(Upload::class, 'banner_upload_id');
    }
}
