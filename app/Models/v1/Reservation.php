<?php

namespace App\Models\v1;

use App\UuidForKey;
use Carbon\Carbon;
use Conner\Tagging\Taggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Collection;

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
        'given_names', 'surname', 'gender', 'status',
        'shirt_size', 'birthday', 'user_id',
        'trip_id', 'rep_id', 'todos', 'companions', 'costs',
        'passport_id'
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
     * Get the trip that owns the reservation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Get all of the reservation's companions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companions()
    {
        return $this->hasMany(Companion::class);
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

    public function activeCosts()
    {
        return $this->belongsToMany(Cost::class, 'reservation_costs')
                    ->active()
                    ->withPivot('locked')
                    ->withTimestamps();
    }

    public function dues()
    {
        return $this->hasMany(Due::class);
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
                    ->withTimestamps();
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
        return $this->belongsToMany(Requirement::class, 'reservation_requirements')
                    ->withPivot('grace_period', 'status', 'completed_at')
                    ->withTimestamps();
    }

    /**
     * Get all of the reservation's fundraisers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function fundraisers()
    {
        return $this->morphMany(Fundraiser::class, 'fundable');
    }

    /**
     * Get all of the reservation's donations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function donations()
    {
        return $this->morphMany(Donation::class, 'designation');
    }

    /**
     * Get the reservation's passport.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function passport()
    {
        return $this->belongsTo(Passport::class);
    }

    /**
     * Get the reservation's visa.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function visa()
    {
        return $this->belongsTo(Visa::class);
    }

    /**
     * Get the reservation's team member details
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function membership()
    {
        return $this->morphOne(TeamMember::class, 'assignable');
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
        return $this->costs()->sum('amount');
    }

    /**
     * Get the total amount raised for the reservation.
     *
     * @return mixed
     */
    public function getTotalRaised()
    {
        return $this->donations()->sum('amount');
    }

    /**
     * Get the total amount still owed for the reservation.
     *
     * @return mixed
     */
    public function getTotalOwed()
    {
        return $this->getTotalCost() - $this->getTotalRaised();
    }

    /**
     * Syncronize all the trip's costs.
     *
     * @param $costs
     */
    public function syncCosts($costs)
    {
        if ( ! $costs) return;
        
        if ( ! $costs instanceof Collection)
            $costs = collect($costs);

        $data = $costs->pluck('id')->all();
        
        $this->costs()->sync($data);
    }

    public function addDues($dues)
    {
        if ( ! $dues) return;

        if ( ! $dues instanceof Collection)
            $dues = collect($dues);

        $data = $dues->map(function($due) {
            return new Due($due);
        })->all();

        $this->dues()->saveMany($data);
    }

    /**
     * Syncronize all the reservation's requirements.
     *
     * @param $requirements
     */
    public function syncRequirements($requirements)
    {
        if ( ! $requirements) return;

        if ( ! $requirements instanceof Collection)
            $requirements = collect($requirements);

        $data = $requirements->keyBy('id')->map(function($item, $key) {
            return [
                'grace_period' => $item->grace_period,
                'status' => $item->status ? $item->status : 'incomplete',
                'completed_at' => $item->completed_at ? $item->completed_at : null
            ];
        })->toArray();

        $this->requirements()->sync($data);
    }

    /**
     * Syncronize all the reservation's deadlines.
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
                'grace_period' => $item->grace_period
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
     * Syncronize all the reservation's todos.
     *
     * @param $todos
     */
    public function syncTodos($todos)
    {
        if ( ! $todos) return;

        $ids = $this->todos()->lists('id', 'id');

        foreach($todos as $todo)
        {
            if( ! isset($todo['id'])) $todo['id'] = null;

            array_forget($ids, $todo['id']);

            $this->todos()->updateOrCreate(['id' => $todo['id']], $todos);
        }

        if( ! $ids->isEmpty()) $this->todos()->delete($ids);
    }
}
