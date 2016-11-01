<?php

namespace App\Models\v1;

use App\UuidForKey;
use Conner\Tagging\Taggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Fundraiser extends Model
{
    use Filterable, UuidForKey, Taggable;

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
        'sponsor_id', 'sponsor_type', 'url', 'type', 'public'
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
     * Get the fund the fundraiser belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function fund()
    {
        return $this->belongsTo(Fund::class);
    }

    /**
     * Get all the fundraiser's stories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function stories()
    {
        return $this->morphToMany(Story::class, 'publication', 'published_stories')
            ->withPivot('published_at')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get the donations made through the fundraiser.
     *
     * @return mixed
     */
    public function donations()
    {
        return $this->fund->donations()
                          ->from($this->started_at)
                          ->to($this->ended_at);
    }

    /**
     * Get the donors who gave through the fundraiser.
     *
     * @return mixed
     */
    public function donors()
    {
        return $this->fund
                    ->donors()
                    ->wherePivot('created_at', '>=', $this->started_at)
                    ->wherePivot('created_at', '<=', $this->ended_at);
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
     * Get the fundraiser's uploads.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function uploads()
    {
        return $this->morphToMany(Upload::class, 'uploadable');
    }

}
