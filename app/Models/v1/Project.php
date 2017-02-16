<?php

namespace App\Models\v1;

use App\Jobs\SyncProjectPaymentsDue;
use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Project extends Model
{
    use Filterable, SoftDeletes, UuidForKey;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'funded_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should not be mass assigned.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['percent_raised', 'amount_raised', 'goal', 'amount_outstanding'];

    /**
     * Get the project's sponsor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function sponsor()
    {
        return $this->morphTo('sponsor');
    }

    /**
     * Get the projects user sponsor.
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'sponsor_id')->where('sponsor_type', 'users');
    }

    /**
     * Get the projects group sponsor.
     *
     * @return mixed
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'sponsor_id')->where('sponsor_type', 'groups');
    }

    /**
     * Get the project's initiative.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function initiative()
    {
        return $this->belongsTo(ProjectInitiative::class, 'project_initiative_id');
    }

    /**
     * Notes attached to the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    /**
     * Get the project's fund.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function fund()
    {
        return $this->morphOne(Fund::class, 'fundable');
    }

    /**
     * Get the project's costs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function costs()
    {
        return $this->morphMany(Cost::class, 'cost_assignable');
    }

    /**
     * Get the project's deadlines.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function deadlines()
    {
        return $this->morphMany(Deadline::class, 'deadline_assignable');
    }

    /**
     * Get the project's goal.
     *
     * @return int
     */
    public function getGoalAttribute()
    {
        return $this->costs ? $this->costs()->sum('amount') : 0;
    }

    /**
     * Get the project's amount raised.
     *
     * @return int
     */
    public function getAmountRaisedAttribute()
    {
        return $this->fund ? $this->fund->balance : 0;
    }

    /**
     * Get the project's percentage raised.
     *
     * @return float|int
     */
    public function getPercentRaisedAttribute()
    {
        if ($this->amount_raised == 0 or $this->goal == 0) {
            return 0;
        }

        return round(($this->amount_raised/$this->goal) * 100);
    }

    /**
     * Get the amount still outstanding.
     *
     * @return int|mixed
     */
    public function getAmountOutstandingAttribute()
    {
        $difference = $this->goal - $this->amount_raised;

        if ($difference < 0) return 0;

        return $difference;
    }

    public function goalInDollars()
    {
        return number_format($this->goal/100, 2);
    }

    public function amountRaisedInDollars()
    {
        return number_format($this->amount_raised/100, 2);
    }

    /**
     * Get new projects.
     *
     * @param $query
     * @return mixed
     */
    public function scopeNew($query)
    {
        return $query->whereHas('initiative', function($initiative) {
            return $initiative->new();
        });
    }

    /**
     * Get old projects.
     *
     * @param $query
     * @return mixed
     */
    public function scopeOld($query)
    {
        return $query->whereHas('initiative', function($initiative) {
            return $initiative->past();
        });
    }

    /**
     * Get funded projects.
     *
     * @param $query
     * @return mixed
     */
    public function scopeFunded($query)
    {
        return $query->whereNotNull('funded_at');
    }

    /**
     * Sync costs with the project.
     *
     * @param $costs
     */
    public function syncCosts($costs)
    {
        if ( ! $costs) return;

        if ( ! $costs instanceof Collection)
            $costs = collect($costs);

        $data = $costs->keyBy('id')->map(function($item) {
            return [
//                'quantity' => $item['quantity'],
            ];
        })->toArray();

        $this->costs()->sync($data);

        dispatch(new SyncProjectPaymentsDue($this));
    }

    /**
     * Project Dues.
     *
     * @return mixed
     */
    public function dues()
    {
        return $this->morphMany(Due::class, 'payable')->sortRecent();
    }

    /**
     * Manage a Project's Payments.
     *
     * @return ProjectPayment
     */
    public function payments()
    {
        return new ProjectPayment($this);
    }

    public function rep()
    {
        return $this->belongsTo(User::class, 'rep_id');
    }
}
