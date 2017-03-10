<?php

namespace App\Models\v1;

use Carbon\Carbon;
use App\UuidForKey;
use Conner\Tagging\Taggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\Reservations\SyncPaymentsDue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use SoftDeletes, Filterable, UuidForKey, Taggable;

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
     * Get the user that owns the reservation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rep()
    {
        return $this->belongsTo(User::class);
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
                    ->withPivot('relationship');
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
        return $this->fund->fundraisers();
    }

    public function fund()
    {
        return $this->morphOne(Fund::class, 'fundable');
    }

    public function donations()
    {
        return $this->fund->donations();
    }

    public function donors()
    {
        return $this->fund()->donors();
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
     * Synchronize all the reservation's costs.
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
                'locked' => isset($item['locked']) and $item['locked'] ? true : false,
            ];
        })->toArray();
        
        $this->costs()->sync($data);

        if ($this->fundraisers->count())
            $this->fundraisers()->first()->update([
                'goal_amount' => $this->getTotalCost()/100
            ]);

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
            $this->requirements()->create($requirement);
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
}
