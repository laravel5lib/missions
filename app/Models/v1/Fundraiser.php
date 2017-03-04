<?php

namespace App\Models\v1;

use Carbon\Carbon;
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
        'sponsor_id', 'sponsor_type', 'url', 'type', 'public', 'show_donors',
        'created_at', 'updated_at'
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
        return $this->fund->donations();
    }

    /**
     * Get all the transactions related to the fundraiser.
     *
     * @return mixed
     */
    public function transactions()
    {
        return $this->fund->transactions;
    }

    /**
     * Get the donors who gave through the fundraiser.
     *
     * @return mixed
     */
    public function donors()
    {
        return $this->fund->donors();
    }

    /**
     * Get the fundraiser's total amount raised.
     *
     * @return integer
     */
    public function raised()
    {
        $amount = $this->transactions()->sum('amount'); // in cents

        return $amount ? (int) $amount : 0;
    }

    /**
     * Get the fundraiser's total amount raised in dollars.
     * 
     * @return string
     */
    public function raisedAsDollars()
    {
        return number_format(($this->raised() / 100), 2, '.', '');
    }

    /**
     * Set the fundraiser's goal amount in cents.
     * 
     * @param integet $value
     */
    public function setGoalAmountAttribute($value)
    {
        $this->attributes['goal_amount'] = $value*100; // convert to cents
    }

    /**
     * Get the fundraiser's goal amount in dollars.
     * 
     * @return string
     */
    public function goalAmountAsDollars()
    {
        return number_format(($this->goal_amount / 100), 2, '.', '');
    }

    /**
     * Get the fundraiser's percentage raised.
     * 
     * @return integer
     */
    public function getPercentRaised()
    {
        // check for 0 values first,
        // because division by zero is not possible
        if( $this->raised() === 0 or $this->goal_amount === 0 )
            return 0;

        return (int) round(($this->raised()/$this->goal_amount) * 100);
    }

    /**
     * Get the Fundraiser's Status.
     * 
     * @return string
     */
    public function getStatus()
    {
        if ($this->ended_at->isPast()) {
            return 'closed';
        }

        if ($this->getPercentRaised() >= 100) {
            $this->ended_at = Carbon::now();
            $this->save();
            
            return 'closed';
        }

        return 'open';
    }

    /**
     * Check if fundraiser is closed.
     * 
     * @return boolean
     */
    public function isClosed()
    {
        if ($this->getStatus() == 'closed')
            return true;

        return false;
    }

    /**
     * Check if fundraiser is closed.
     * 
     * @return boolean
     */
    public function isOpen()
    {
        if ($this->getStatus() == 'open')
            return true;

        return false;
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

    /**
     * Get public fundraisers
     * 
     * @param  Builder $query
     * @return Builder
     */
    public function scopePublic($query)
    {
        return $query->where('public', true);
    }

}
