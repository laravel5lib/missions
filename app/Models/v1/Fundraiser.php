<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

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

    /**
     * Get the donations made through the fundraiser.
     *
     * @return mixed
     */
    public function donations()
    {
        // we limit the results by passing in the fundraiser dates.
        return $this->fundable->fund->donations($this->started_at, $this->ended_at);
    }

    /**
     * Get the donors you gave through the fundraiser.
     *
     * @return mixed
     */
    public function donors()
    {
        // we limit the results by passing in the fundraiser dates.
        return $this->fundable->fund->donors($this->started_at, $this->ended_at);
    }

    /**
     * Get the fundraiser's total amount raised.
     *
     * @return mixed
     */
    public function raised()
    {
        return $this->donations()->sum('amount');
    }

    public function getPercentRaised()
    {
        // check for 0 values first,
        // because division by zero is not possible
        if( $this->raised() === 0 or $this->goal_amount === 0 )
            return 0;

        return round(($this->raised()/$this->goal_amount) * 100);
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
