<?php

namespace App\Models\v1;

use Carbon\Carbon;
use App\UuidForKey;
use App\Traits\Rewardable;
use Conner\Tagging\Taggable;
use EloquentFilter\Filterable;
use Illuminate\Support\Facades\DB;
use App\Models\v1\RequirementCondition;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\Reservations\SyncPaymentsDue;
use Illuminate\Database\Eloquent\Collection;
use App\Jobs\Reservations\ProcessReservation;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use SoftDeletes, Filterable, UuidForKey, Taggable, Rewardable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reservations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'given_names', 'surname', 'gender', 'status',
        'shirt_size', 'birthday', 'phone_one', 'phone_two',
        'address', 'city', 'state', 'zip', 'country_code',
        'trip_id', 'rep_id', 'todos', 'companion_limit', 'costs',
        'passport_id', 'user_id', 'email', 'avatar_upload_id',
        'arrival_designation', 'testimony_id', 'desired_role',
        'shirt_size', 'height', 'weight', 'created_at', 'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'birthday', 'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * All of the relationships to be touched.
     * Update the parent's timestamp.
     *
     * @var array
     */
    protected $touches = ['trip'];

    /**
     * Indicates if the model should be timestamped
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Set the reservation's gender.
     *
     * @param $value
     */
    public function setGenderAttribute($value)
    {
        if ($value)
            $this->attributes['gender'] = trim(strtolower($value));
    }

    /**
     * Get the reservation's gender.
     *
     * @param $value
     * @return string
     */
    public function getGenderAttribute($value)
    {
        return $value ? strtolower($value) : null;
    }

    /**
     * Get the reservation's companion limi.
     * 
     * @param  integer $value
     * @return integer
     */
    public function getCompanionLimitAttribute($value)
    {
        return $value ?: ($this->trip->companion_limit ?: 0);
    }

    /**
     * Set the reservation's status.
     *
     * @param $value
     */
    public function setStatusAttribute($value)
    {
        if ($value)
            $this->attributes['status'] = trim(strtolower($value));
    }

    /**
     * Get the reservation's status.
     *
     * @param $value
     * @return string
     */
    public function getStatusAttribute($value)
    {
        return $value ? strtolower($value) : null;
    }

    public function designation()
    {
        return $this->hasOne(Questionnaire::class, 'reservation_id')
                    ->where('type', 'arrival_designation');
    }

    /**
     * Team squads the reservation is assigned to.
     * 
     * @return BelongsToMany
     */
    public function squads()
    {
        return $this->belongsToMany(TeamSquad::class, 'team_members')
                    ->withPivot('leader')
                    ->withTimestamps();
    }

    /**
     * Get the user that owns the reservation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rep()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * Get the trip that owns the reservation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Get all of the reservation's companion reservations
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function companionReservations()
    {
        return $this->belongsToMany(Reservation::class, 'companions', 'companion_id')
                    ->withPivot('relationship')->distinct();
    }

    /**
     * Get the reservation's companions.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companions()
    {
        return $this->hasMany(Companion::class, 'reservation_id');
    }

    /**
     * Get all of the reservation's costs
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function costs()
    {
        return $this->belongsToMany(Cost::class, 'reservation_costs')
                    ->withPivot('locked')
                    ->withTimestamps();
    }

    /**
     * Get all of the reservation's active costs.
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function activeCosts()
    {
        return $this->belongsToMany(Cost::class, 'reservation_costs')
                    ->active()
                    ->withPivot('locked')
                    ->withTimestamps();
    }

    /**
     * Get all the reservation's payments due.
     * 
     * @return Illuminate\Database\Eloquent\Reservations\MorphMany
     */
    public function dues()
    {
        return $this->morphMany(Due::class, 'payable')->sortRecent();
    }

    /**
     * Get all of the reservation's deadlines
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function deadlines()
    {
        return $this->belongsToMany(Deadline::class, 'reservation_deadlines')
                    ->withPivot('grace_period')
                    ->withTimestamps()
                    ->orderBy('date', 'desc');
    }

    /**
     * Get all of the reservation's todos
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function todos()
    {
        return $this->morphMany(Todo::class, 'todoable');
    }

    /**
     * Get all of the reservation's notes
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    /**
     * Get all of the reservation's requirements
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function requirements()
    {
        return $this->hasMany(ReservationRequirement::class);
    }

    /**
     * Get all of the reservation's fundraisers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function fundraisers()
    {
        return $this->fund->fundraisers()->withTrashed();
    }

    public function fund()
    {
        return $this->morphOne(Fund::class, 'fundable')->withTrashed();
    }

    public function donations()
    {
        return $this->fund->donations();
    }

    public function donors()
    {
        return $this->fund()->donors();
    }

    public function promocodes()
    {
        return $this->morphMany(Promocode::class, 'rewardable');
    }

    public function transports()
    {
        return $this->belongsToMany(Transport::class, 'passengers');
    }

    /**
     * Get the reservation's avatar.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function avatar()
    {
        return $this->belongsTo(Upload::class, 'avatar_upload_id');
    }

    /**
     * Get the name on the reservation.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->given_names.' '.$this->surname;
    }

    /**
     * Get the name on the reservation.
     *
     * @return string
     */
    public function getAgeAttribute()
    {
        if ( ! is_null($this->birthday))
        {
            return $this->birthday->diffInYears();
        }
    }

    /**
     * Get the total cost for the reservation.
     *
     * @return mixed
     */
    public function getTotalCost()
    {
        return $this->costs ? (int) $this->costs()->sum('amount') : 0;
    }

    /**
     * Get reservation's total cost in dollars.
     * 
     * @return string
     */
    public function totalCostInDollars()
    {
        return number_format($this->getTotalCost()/100, 2, '.', '');
    }

    /**
     * Get the total of what was raised.
     *
     * @return float
     */
    public function getTotalRaised()
    {
        return $this->fund ? $this->fund->balance : 0;
    }

    public function totalRaisedInDollars()
    {
        return number_format($this->getTotalRaised()/100, 2, '.', '');
    }

    /**
     * Get the percentage of what was raised.
     *
     * @return float
     */
    public function getPercentRaised()
    {
        if( $this->getTotalRaised() === 0 or $this->getTotalCost() === 0 )
            return 0;

        return (int) round(($this->getTotalRaised()/$this->getTotalCost()) * 100);
    }

    /**
     * Get the total amount still owed for the reservation.
     *
     * @return mixed
     */
    public function getTotalOwed()
    {
        $total = $this->getTotalCost() - $this->getTotalRaised();

        return $total > 0 ? $total : 0;
    }

    public function totalOwedInDollars()
    {
        return number_format($this->getTotalOwed()/100, 2 , '.', '');
    }

    /**
     * Update the reservation's costs
     */
    public function updateCosts()
    {
        // first, we get all of a trip's active costs
        $active = $this->trip
                       ->activeCosts()
                       ->get()
                       ->transform(function($cost) {
                            // set a "locked" attribute for 
                            // merge with reseration costs
                            return $cost->setAttribute('locked', 0);
                       });

        // we find the 'incremental' cost with the latest activation date
        $maxDate = $active->where('type', 'incremental')->max('active_at');

        // we remove optional costs so they don't 
        // get added to the reservation
        $tripCosts = $active->reject(function ($value) {
            return $value->type == 'optional';
            // we remove any incremental costs that are not the most 
            // recently activated cost to make sure they don't 
            // get added to the reservation
        })->reject(function ($value) use ($maxDate) {
            return $value->type == 'incremental' && $value->active_at < $maxDate;
        });

        // 1) remove any reservation "incremental" costs that are "overdue" and not locked
        // 2) merge new trip costs with existing reservation costs
        // 3) remove duplicates
        $costs = $this->costs
                    ->transform(function($cost) {
                        // set a "locked" attribute to make sure any previously
                        // locked costs remain locked after update
                        return $cost->setAttribute('locked', $cost->pivot->locked);
                    })
                    ->reject(function($cost) {
                        return in_array(
                            $cost->id, 
                            $this->payments()
                                 ->late()
                                 ->pluck('payment.cost_id')
                                 ->toArray()
                        ) and ! $cost->pivot->locked and $cost->type == 'incremental';
                    })
                    ->merge($tripCosts)
                    ->unique();

        // find the 'incremental' cost that has the earliest activation date
        $minDate = $costs->where('type', 'incremental')->min('active_at');

        // to make sure only one incremental cost is being applied,
        // we choose the 'incremental' cost that 
        // has the earliest activation date
        $costs = $costs->reject(function ($value) use($minDate) {
            return $value->type == 'incremental' && $value->active_at > $minDate;
        });

        // send the final filtered costs to be synced
        $this->syncCosts($costs);
    }

    /**
     * Synchronize all the reservation's costs.
     *
     * @param $costs
     */
    public function syncCosts($costs)
    {
        if ( ! $costs) return;
        
        if ( ! $costs instanceof Collection)
            $costs = collect($costs);

        // build the data array
        $data = $costs->keyBy('id')->map(function($item) {
            return [
                'locked' => isset($item['locked']) and $item['locked'] ? true : false,
            ];
        })->toArray();
        
        $this->costs()->sync($data);

        // update the related fudraiser goal amount
        if ($this->fundraisers->count())
            $this->fundraisers()->first()->update([
                'goal_amount' => $this->getTotalCost()/100
            ]);

        // go update the payments due
        dispatch(new SyncPaymentsDue($this));
    }

    /**
     * Manage a Reservation's Payments.
     *
     * @return ReservationPayment
     */
    public function payments()
    {
        return new ReservationPayment($this);
    }

    /**
     * Synchronize all the reservation's requirements.
     *
     * @param $requirements
     */
    public function syncRequirements($requirements)
    {
        if ( ! $requirements) return;

        if ( ! $requirements instanceof Collection)
            $requirements = collect($requirements);

        $requirements->map(function($item, $key) {
            return [
                'requirement_id' => $item->id,
                'document_type' => $item->document_type,
                'grace_period' => $item->grace_period,
                'status' => $item->status ? $item->status : 'incomplete',
                'completed_at' => $item->completed_at ? $item->completed_at : null
            ];
        })->each(function($requirement) {

            // if conditions apply
            if (RequirementCondition::where('requirement_id', $requirement['requirement_id'])->count()) {

                    $matches = collect([]);

                    RequirementCondition::where('requirement_id', $requirement['requirement_id'])->get()->each(function($condition) use($matches) {
                        if ($condition->type === 'role') {
                            $matches->push(in_array($this->desired_role, $condition->applies_to));
                        } elseif ($condition->type === 'age') {
                            $matches->push($this->age < (int) $condition->applies_to[0]);
                        } else {
                            $matches->push(false);
                        }
                    });

                    // if all conditions match
                    if ( ! in_array(false, $matches->all())) {
                        $this->requirements()->create($requirement);
                    }

            } else {
                // if not conditions apply
                $this->requirements()->create($requirement);
            }
        });
    }

    /**
     * Synchronize all the reservation's deadlines.
     *
     * @param $deadlines
     */
    public function syncDeadlines($deadlines)
    {
        if ( ! $deadlines) return;

        if ( ! $deadlines instanceof Collection)
            $deadlines = collect($deadlines);

        $data = $deadlines->keyBy('id')->map(function($item, $key) {
            return [
                'grace_period' => $item['grace_period']
            ];
        })->toArray();

        $this->deadlines()->sync($data);
    }

    /**
     * Add an array of todos to the reservation.
     * 
     * @param array $todos [description]
     */
    public function addTodos(array $todos)
    {
        if ( ! $todos) return;

        $data = collect($todos)->map(function($item, $key) {
            return new Todo(['task' => $item]);
        });

        $this->todos()->saveMany($data);
    }

    /**
     * Delete an array of todos from the reservation.
     * 
     * @param  array  $todos
     */
    public function removeTodos(array $todos)
    {
        if ( ! $todos) return;

        $this->todos()
             ->whereIn('task', $todos)
             ->whereNull('completed_at')
             ->delete();
    }

    /**
     * Synchronize all the reservation's todos.
     *
     * @param $tripTodos
     */
    public function syncTodos(array $tripTodos)
    {
        $tasks = $this->todos()->pluck('task')->toArray();
        
        $todos = collect($tripTodos)->transform(function($todo) {
            return ucfirst(trim(strtolower($todo)));
        })->toArray();

        $oldTodos = collect($tasks)->reject(function ($task) use($todos) {
            return in_array($task, $todos);
        })->all();
        $this->removeTodos($oldTodos);

        $newTodos = collect($todos)->reject(function ($todo) use($tasks) {
            return in_array($todo, $tasks);
        })->all();
        $this->addTodos($newTodos);
    }

    /**
     * Get current reservations
     * 
     * @param  Builder $query
     * @return Builder
     */
    public function scopeCurrent($query)
    {
        return $query->whereHas('trip', function($trip) {
            return $trip->where('ended_at', '>', Carbon::now());
        });
    }

    /**
     * Helper method to retrieve the user's avatar
     * 
     * @return mixed
     */
    public function getAvatar()
    {
        if( ! $this->avatar) {
            return new Upload([
                'id' => \Ramsey\Uuid\Uuid::uuid4(),
                'name' => 'placeholder',
                'type' => 'avatar',
                'source' => 'images/placeholders/user-placeholder.png',
                'meta' => null,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]);
        }

        return $this->avatar;
    }

    /**
     * Drop the reservation
     */
    public function drop()
    {
        $this->archiveFund()
             ->removeFromSquads();

        $this->delete();
    }

    /**
     * Archive the Reservation's Fund.
     */
    public function archiveFund()
    {
        if ($this->fund)
            $this->fund->archive();

        return $this;
    }

    /**
     * Remove the reservation from all squads.
     */
    public function removeFromSquads()
    {
        $this->squads()->detach();

        return $this;
    }

    /**
     * Restore a dropped reservation
     */
    public function undoDrop()
    {
        $this->fund ? $this->fund->reactivate() : null;

        $this->restore();
    }

    /**
     * Transfer Reservation to another trip
     * 
     * @param  String $trip_id
     * @param  String $desired_role
     * @return void
     */
    public function transferToTrip($trip_id, $desired_role)
    {
        DB::transaction(function () use($trip_id, $desired_role) {
            // remove the current resources
            $this->costs()->detach();
            $this->requirements()->delete();
            $this->todos()->delete();
            $this->squads()->detach();

            // change trip and desired role
            $this->update([
                'desired_role' => $desired_role,
                'trip_id' => $trip_id
            ]);
        });

        // sync all other resources
        dispatch(new ProcessReservation($this));
    }

    /**
     * Find rewardable promotionals the 
     * reservation can be enrolled in
     * 
     * @return mixed
     */
    public function canBeRewarded()
    {
        $promos = collect([]);

        $this->trip->campaign
             ->promotionals()
             ->active()
             ->hasAffiliates('reservations')
             ->pluck('id')
             ->each(function ($id) use($promos) {
                $promos->push($id);
            });

        $this->trip
             ->promotionals()
             ->active()
             ->hasAffiliates('reservations')
             ->pluck('id')
             ->each(function ($id) use($promos) {
                $promos->push($id);
            });

        $this->trip->group
             ->promotionals()
             ->active()
             ->hasAffiliates('reservations')
             ->pluck('id')
             ->each(function ($id) use($promos) {
                $promos->push($id);
            });

        return $promos->count() ? $promos : false;
    }
}
