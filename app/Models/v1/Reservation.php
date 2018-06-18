<?php

namespace App\Models\v1;

use Carbon\Carbon;
use App\UuidForKey;
use App\ItemizedPricing;
use App\Models\v1\Payment;
use App\Traits\HasPricing;
use App\Traits\Rewardable;
use App\TransferReservation;
use Conner\Tagging\Taggable;
use App\Utilities\v1\TeamRole;
use EloquentFilter\Filterable;
use Spatie\QueryBuilder\Filter;
use App\Events\ReservationCreated;
use App\Models\v1\FlightItinerary;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;
use App\Events\ReservationWasProcessed;
use App\Models\v1\RequirementCondition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Jobs\Reservations\SyncPaymentsDue;
use Illuminate\Database\Eloquent\Collection;
use App\Jobs\Reservations\ProcessReservation;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Presenters\ReservationPresenter;

class Reservation extends Model
{
    use SoftDeletes, Filterable, UuidForKey, Taggable, Rewardable, ReservationPresenter, HasPricing, Notifiable;

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
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => ReservationCreated::class
    ];

    /**
     * Set the reservation's gender.
     *
     * @param $value
     */
    public function setGenderAttribute($value)
    {
        if ($value) {
            $this->attributes['gender'] = trim(strtolower($value));
        }
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
        if ($value) {
            $this->attributes['status'] = trim(strtolower($value));
        }
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
                    ->has('team')
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

    public function flightItinerary()
    {
        return $this->belongsTo(FlightItinerary::class);
    }

    public function passport()
    {
        return $this->hasMany(ReservationRequirement::class)
            ->where('document_type', 'passports')
            ->whereNotNull('document_id')
            ->take(1);
            
        // $requirement = $this->requirements()
        //     ->where('document_type', 'passports')
        //     ->whereNotNull('document_id')
        //     ->first();
        
        // return $requirement ?? $requirement->document;
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
     * Get the reservation's room assignments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'occupants')
                    ->withPivot('room_leader')
                    ->withTimestamps();
    }

    public function questionnaires()
    {
        return $this->hasMany(Questionnaire::class);
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
        if (! is_null($this->birthday)) {
            return $this->birthday->diffInYears();
        }
    }

    /**
     * Get the requested room
     *
     * @return mixed
     */
    public function getRequestedRoomAttribute()
    {
        $room = $this->priceables()->whereHas('cost', function($q) {
            return $q->whereType('optional');
        })->first();

        return $room ? $room->cost->name : null;
    }

    /**
     * Get the total cost for the reservation.
     *
     * @return mixed
     */
    public function getTotalCost()
    {
        return $this->priceables ? (int) $this->priceables()->sum('amount') : 0;
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
        if ($this->getTotalRaised() === 0) {
            return 0;
        }

        if($this->getTotalCost() === 0) {
            return 100;
        }

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
        return number_format($this->getTotalOwed()/100, 2, '.', '');
    }

    /**
     * Synchronize all the reservation's requirements.
     *
     * @param $requirements
     */
    public function syncRequirements($requirements)
    {
        if (! $requirements) {
            return;
        }

        if (! $requirements instanceof Collection) {
            $requirements = collect($requirements);
        }

        $requirements->map(function ($item, $key) {
            return [
                'requirement_id' => $item->id,
                'document_type' => $item->document_type,
                'grace_period' => $item->grace_period,
                'status' => $item->status ? $item->status : 'incomplete',
                'completed_at' => $item->completed_at ? $item->completed_at : null
            ];
        })->each(function ($requirement) {


            if ($requirement['document_type'] === 'minor_releases') {

                $this->age < 19 ? $this->requirements()->create($requirement) : null;
            
            } elseif ($requirement['document_type'] === 'media_credentials') {

                $this->desired_role === 'MEDI' ? $this->requirements()->create($requirement) : null;

            } elseif ($requirement['document_type'] === 'medical_credentials') {

                in_array($this->desired_role, array_keys(TeamRole::medical())) 
                ? $this->requirements()->create($requirement) 
                : null;
                
            } else {
                $this->requirements()->create($requirement);
            }

            // if conditions apply
            // if (RequirementCondition::where('requirement_id', $requirement['requirement_id'])->count()) {
            //         $matches = collect([]);

            //         RequirementCondition::where('requirement_id', $requirement['requirement_id'])->get()->each(function ($condition) use ($matches) {
            //             if ($condition->type === 'role') {
            //                 $matches->push(in_array($this->desired_role, $condition->applies_to));
            //             } elseif ($condition->type === 'age') {
            //                 $matches->push($this->age < (int) $condition->applies_to[0]);
            //             } else {
            //                 $matches->push(false);
            //             }
            //         });

            //         // if all conditions match
            //     if (! in_array(false, $matches->all())) {
            //         $this->requirements()->create($requirement);
            //     }
            // } else {
            //     // if not conditions apply
            //     $this->requirements()->create($requirement);
            // }
        });
    }

    /**
     * Synchronize all the reservation's deadlines.
     *
     * @param $deadlines
     */
    public function syncDeadlines($deadlines)
    {
        if (! $deadlines) {
            return;
        }

        if (! $deadlines instanceof Collection) {
            $deadlines = collect($deadlines);
        }

        $data = $deadlines->keyBy('id')->map(function ($item, $key) {
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
    public function addTodos($todos = [])
    {
        if (! $todos) {
            return;
        }

        $data = collect($todos)->map(function ($item, $key) {
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
        if (! $todos) {
            return;
        }

        $todos = array_map('strtolower', $todos);

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

        $todos = collect($tripTodos)->transform(function ($todo) {
            return ucfirst(trim(strtolower($todo)));
        })->toArray();

        $oldTodos = collect($tasks)->reject(function ($task) use ($todos) {
            return in_array($task, $todos);
        })->all();
        $this->removeTodos($oldTodos);

        $newTodos = collect($todos)->reject(function ($todo) use ($tasks) {
            return in_array($todo, $tasks);
        })->all();
        $this->addTodos($newTodos);
    }

    public function scopeCurrent($query)
    {
        return $query->whereHas('trip', function ($trip) {
            return $trip->current();
        });
    }

    public function scopeActive($query)
    {
        return $this->scopeCurrent($query);
    }

    public function scopePast($query)
    {
        return $query->whereHas('trip', function ($trip) {
            return $trip->past();
        });
    }

    public function scopeRecordLocator($query, $record)
    {
        return $query->whereHas('flightItinerary', function ($subQuery) use ($record) {
            return $subQuery->where('record_locator', 'like', "%$record%");
        });
    }

    public function scopeFlightNo($query, $number)
    {
        return $query->whereHas('flightItinerary.flights', function ($subQuery) use ($number) {
            return $subQuery->where('flight_no', 'like', "%$number%");
        });
    }

    public function scopeIataCode($query, $code)
    {
        return $query->whereHas('flightItinerary.flights', function ($subQuery) use ($code) {
            return $subQuery->where('iata_code', 'like', "%$code%");
        });
    }

    public function scopeGroup($query, $uuid)
    {
        return $query->whereHas('trip', function ($subQuery) use ($uuid) {
            return $subQuery->where('group_id', $uuid);
        });
    }

    public function scopeTripType($query, $type)
    {
        return $query->whereHas('trip', function ($subQuery) use ($type) {
            return $subQuery->where('type', $type);
        });
    }

    public function scopeCampaign($query, $uuid)
    {
        return $query->whereHas('trip', function ($subQuery) use ($uuid) {
            return $subQuery->where('campaign_id', $uuid);
        });
    }

    public function scopeHasFlight($query, $hasFlight)
    {
        return $hasFlight 
            ? $query->whereNotNull('flight_itinerary_id') 
            : $query->whereNull('flight_itinerary_id');
    }

    public function scopePassportNumber($query, $number)
    {
        return $query->whereHas('passport', function ($subQuery) use ($number) {

            return $subQuery->join('passports', 'passports.id', '=', 'reservation_requirements.document_id')
                ->where('passports.number', 'LIKE', "$number%");
        });
    }

    public function scopeCost($query, $uuid)
    {
        $query->whereHas('priceables', function($subQuery) use($uuid) {
            return $subQuery->whereHas('cost', function ($q) use($uuid) {
                return $q->where('id', $uuid);
            });
        });
    }

    public function scopeAgeRange($query, ...$ages)
    {
        if(isset($ages[1])) {
            $startDob = Carbon::now()->startOfYear()->subYears($ages[1]);
            $endDob = Carbon::now()->startOfYear()->subYears($ages[0]);

            $query->whereBetween('birthday', [$startDob, $endDob]);
        } else {
            $dob = Carbon::now()->startOfYear()->subYears($ages[0]);

            $query->where('birthday', '<=', $dob);
        }
    }

    public function scopePercentRaisedRange($query, ...$percentages)
    {
        $startDecimal = $percentages[0] / 100;
        $endDecimal = isset($percentages[1]) ? $percentages[1] / 100 : null;

        $ids = DB::table('reservations')
            ->join('priceables', 'priceables.priceable_id', '=', 'reservations.id')
            ->join('prices', 'prices.id', '=', 'priceables.price_id')
            ->join('funds', 'reservations.id', '=', 'funds.fundable_id')
            ->groupBy('reservations.id')
            ->havingRaw("funds.balance/SUM(prices.amount) >= $startDecimal")
            ->when($endDecimal, function ($query) use ($endDecimal) {
                $query->havingRaw("funds.balance/SUM(prices.amount) <= $endDecimal");
            })
            ->selectRaw('reservations.id, funds.balance, prices.amount')
            ->pluck('reservations.id');

        return $query->whereIn('reservations.id', $ids);
    }

    public function scopeRegisteredBetween($query, ...$dates)
    {
        $query->whereDate('created_at', '>=', $dates[0])
              ->whereDate('created_at', '<=', $dates[1]);
    }

    public function scopeDropped($query)
    {
        $query->onlyTrashed();
    }

    public function scopeDroppedBetween($query, ...$dates)
    {
        $query->whereDate('deleted_at', '>=', $dates[0])
              ->whereDate('deleted_at', '<=', $dates[1]);
    }

    public function scopeDeposited($query)
    {
        $ids = DB::table('reservations')
            ->join('priceables', 'priceables.priceable_id', '=', 'reservations.id')
            ->join('prices', 'prices.id', '=', 'priceables.price_id')
            ->join('costs', 'prices.cost_id', '=', 'costs.id')
            ->join('funds', 'reservations.id', '=', 'funds.fundable_id')
            ->where('costs.type', 'upfront')
            ->whereNull('reservations.deleted_at')
            ->groupBy('reservations.id')
            ->havingRaw("funds.balance <= SUM(prices.amount)")
            ->selectRaw('reservations.id, funds.balance, prices.amount')
            ->pluck('reservations.id');

        return $query->whereIn('reservations.id', $ids);
    }

    public function scopeInProcess($query)
    {
        $ids = DB::table('reservations')
            ->join('priceables', 'priceables.priceable_id', '=', 'reservations.id')
            ->join('prices', 'prices.id', '=', 'priceables.price_id')
            ->join('costs', 'prices.cost_id', '=', 'costs.id')
            ->join('funds', 'reservations.id', '=', 'funds.fundable_id')
            ->where('costs.type', 'upfront')
            ->whereNull('reservations.deleted_at')
            ->groupBy('reservations.id')
            ->havingRaw("funds.balance > SUM(prices.amount)")
            ->selectRaw('reservations.id, funds.balance, prices.amount')
            ->pluck('reservations.id');

        return $query->whereIn('reservations.id', $ids)->percentRaisedRange(0, 99);
    }

    public function scopeFunded($query)
    {
         $query->percentRaisedRange(100)
               ->whereHas('requirements', function($subQuery) {
                   return $subQuery->whereIn('status', ['incomplete', 'reviewing', 'attention']);
               });
    }

    public function scopeReady($query)
    {
        $query->percentRaisedRange(100)
              ->whereDoesntHave('requirements', function($subQuery) {
                  return $subQuery->whereIn('status', ['incomplete', 'reviewing', 'attention']);
              });
    }

    public function scopeFunnel($query, $funnel)
    {
        $query->{camel_case($funnel)}();
    }

    public function scopeItinerary($query, $uuid)
    {
        $query->whereHas('flightItinerary', function ($subQuery) use ($uuid) {
            return $subQuery->whereUuid($uuid);
        });
    }

    public function scopePublishedItinerary($query)
    {
        $query->whereHas('flightItinerary', function ($subQuery) {
            return $subQuery->where('published', true);
        });
    }
    
    /**
     * Helper method to retrieve the user's avatar
     *
     * @return mixed
     */
    public function getAvatar()
    {
        if (! $this->avatar) {
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
        if ($this->fund) {
            $this->fund->archive();
        }

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

    public function transfer()
    {
        return new TransferReservation($this);
    }

    /**
     * Find rewardable promotions the
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
             ->each(function ($id) use ($promos) {
                $promos->push($id);
             });

        $this->trip
             ->promotionals()
             ->active()
             ->hasAffiliates('reservations')
             ->pluck('id')
             ->each(function ($id) use ($promos) {
                $promos->push($id);
             });

        $this->trip->group
             ->promotionals()
             ->active()
             ->hasAffiliates('reservations')
             ->pluck('id')
             ->each(function ($id) use ($promos) {
                $promos->push($id);
             });

        return $promos->count() ? $promos : false;
    }

    public function process($roomingPriceUuid = null)
    {
        // get trip's current rate
        $this->attachPriceToModel($this->trip->getCurrentRate()->id);
        // add all trip's static & upfront costs
        $this->trip
            ->priceables()
            ->whereHas('cost', function($q) {
                return $q->where('type', 'static')
                         ->orWhere('type', 'upfront');
            })
            ->pluck('id')
            ->each(function ($priceId) {
                $this->attachPriceToModel($priceId);
            });
        // add rooming cost selected by the user
        if ($roomingPriceUuid) {
            $this->addPrice(['price_id' => $roomingPriceUuid]);
        }

        $this->syncRequirements($this->trip->requirements);
        $this->syncDeadlines($this->trip->deadlines);
        $this->addTodos($this->trip->todos);

        event(new ReservationWasProcessed($this));
    }

    /**
     * Get Itemized Pricing
     *
     * @return void
     */
    public function itemizedPrices()
    {
        return new ItemizedPricing($this);
    }

    /**
     * Process a late payment
     *
     * @return boolean
     */
    public function processLatePayment()
    {
        if ($this->paymentIsLate() && ! $this->getCurrentRate()->pivot->locked) {
            return $this->penalize();
        }
    }

    /**
     * Determine if payment is late
     *
     * @return boolean
     */
    public function paymentIsLate()
    {
        $currentPayment = $this->getUpcomingPayment();
        
        return $currentPayment->isPastDue() && $this->hasInsufficientFunds($currentPayment);
    }

    /**
     * Determine if the reservation has insufficient funds
     *
     * @param Payment $currentPayment
     * @return boolean
     */
    private function hasInsufficientFunds(Payment $currentPayment)
    {
        return $this->getTotalRaised() <= $this->getTotalCost() * ($currentPayment->percentage_due/100);
    }

    /**
     * Penalize a late payment
     *
     * @return boolean
     */
    public function penalize()
    {
        $this->removePrice($this->getCurrentRate());

        return $this->attachPriceToModel($this->trip->getCurrentRate()->id);
    }

    public static function getQuery()
    {
        return QueryBuilder::for(static::class)
                ->allowedFilters([
                    'surname', 'given_names', 'email', 'phone_one', 'phone_two',
                    'address', 'city', 'zip', 'state', 'trip_id', 'user_id',
                    Filter::exact('gender'),
                    Filter::exact('status'),
                    Filter::exact('shirt_size'),
                    Filter::exact('desired_role'),
                    Filter::exact('country_code'),
                    Filter::scope('group'),
                    Filter::scope('trip_type'),
                    Filter::scope('campaign'),
                    Filter::scope('has_flight'),
                    Filter::scope('passport_number'),
                    Filter::scope('cost'),
                    Filter::scope('age_range'),
                    Filter::scope('percent_raised_range'),
                    Filter::scope('registered_between'),
                    Filter::scope('dropped'),
                    Filter::scope('dropped_between'),
                    Filter::scope('deposited'),
                    Filter::scope('in_process'),
                    Filter::scope('funded'),
                    Filter::scope('ready'),
                    Filter::scope('funnel'),
                    Filter::scope('current'),
                    Filter::scope('past')
                ])
                ->allowedIncludes(['trip.group', 'trip.campaign', 'passport', 'requirements'])
                ->with('priceables.cost');
    }
}
