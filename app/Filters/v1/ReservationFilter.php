<?php namespace App\Filters\v1;

use App\Utilities\v1\TeamRole;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationFilter extends Filter
{
    use Manageable;

    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [method1, method2]]
    *
    * @var array
    */
    public $relations = [
        'trip' => ['groups', 'campaign', 'type']
    ];

    /**
     * Fields that can be sorted.
     *
     * @var array
     */
    public $sortable = [
        'birthday', 'updated_at', 'created_at',
        'given_names', 'surname', 'email', 'address',
        'city', 'zip', 'country_code', 'phone_one',
        'phone_two', 'deleted_at'
    ];

    /**
     * Fields that can be searched.
     *
     * @var array
     */
    public $searchable = [
        'given_names', 'surname', 'email', 'phone_one',
        'phone_two', 'zip', 'city', 'state'
    ];

    public function ignore($ids)
    {
        if($ids == []) return $this;

        return $this->whereNotIn('id', $ids);
    }

    /**
     * By users.
     *
     * @param $ids
     * @return mixed
     */
    public function user($ids)
    {
        if($ids == []) return $this;

        return $this->whereIn('user_id', $ids);
    }

    /**
     * By shirt sizes.
     *
     * @param $shirtSizes
     * @return mixed
     */
    public function shirtSize($shirtSizes)
    {
        if($shirtSizes == []) return $this;

        return $this->whereIn('shirt_size', $shirtSizes);
    }

    /**
     * By gender.
     *
     * @param $gender
     * @return mixed
     */
    public function gender($gender)
    {
        if( !$gender) return $this;

        return $this->where('gender', $gender);
    }

    /**
     * My relationship status.
     *
     * @param $status
     * @return mixed
     */
    public function status($status)
    {
        if( !$status) return $this;

        return $this->where('status', $status);
    }

    /**
     * By reps.
     *
     * @param $id
     * @return mixed
     */
    public function rep($id)
    {
        if(! $id) return $this;

        return $this->whereHas('trip.rep', function($rep) use($id) {
            return $rep->where('id', $id);
        })->orWhere('rep_id', $id);
    }

    /**
     * By trips.
     *
     * @param $ids
     * @return mixed
     */
    public function trip($ids)
    {
        if($ids == []) return $this;

        return $this->whereIn('trip_id', $ids);
    }

    /**
     * By role.
     * 
     * @param  string $code
     * @return mixed
     */
    public function role($code)
    {
        if(! $code) return $this;

        return $this->where('desired_role', $code);
    }

    /**
     * Between ages.
     *
     * @param $ages
     * @return mixed
     */
    public function age($ages)
    {
        if($ages == []) return $this;

        // $start needs to be the greater number to produce a year earlier than end
        $start = Carbon::now()->subYears($ages[1]);
        $end = Carbon::now()->subYears($ages[0]);

        return $this->whereBetween('birthday', [$start, $end]);
    }

    /**
     * Has a birthday this month.
     *
     * @return mixed
     */
    public function hasBirthday()
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        return $this->whereBetween('birthday', [$start, $end]);
    }

    /**
     * Has travel companions.
     *
     * @param $hasCompanions
     * @return mixed
     */
    public function hasCompanions($hasCompanions)
    {
        if(!$hasCompanions) return $this;

        return $hasCompanions == 'yes' ?
            $this->has('companions') :
            $this->has('companions', '<', 1);
    }

    /**
     * Is currently active.
     *
     * @return mixed
     */
    public function current()
    {
        return $this->active();
    }

    /**
     * Is archived.
     *
     * @return mixed
     */
    public function archived()
    {
        return $this->past();
    }

    /**
     * Is dropped.
     *
     * @return mixed
     */
    public function dropped()
    {
        return $this->onlyTrashed();
    }

    /**
     * By applied cost name
     * 
     * @param  string $name
     * @return mixed
     */
    public function cost($name)
    {
        return $this->whereHas('costs', function($costs) use($name) {
            return $costs->where('name', $name);
        });
    }

    /**
     * By requirement and/or it's status.
     * 
     * @param  String $requirement
     * @return mixed
     */
    public function requirement($requirement)
    {
        $param = preg_split('/\|+/', urldecode($requirement));

        return $this->whereHas('requirements', function($requirements) use($param) 
        {
            if (isset($param[1])) {
                $requirements->where('status', $param[1]);
            }

            return $requirements->whereHas('requirement', function($req) use($param)
            {
                return $req->where('name', $param[0]);
            });
        });
    }

    /**
     * By Todo and it's completion status.
     * 
     * @param  String $todo
     * @return mixed
     */
    public function todo($todo)
    {
        $param = preg_split('/\|+/', urldecode($todo));

        return $this->whereHas('todos', function($todos) use($param)
        {
            if (isset($param[1]) && $param[1] == 'complete') {
                $todos->whereNotNull('completed_at');
            }

            if (isset($param[1]) && $param[1] == 'incomplete') {
                $todos->whereNull('completed_at');
            }
               
            return $todos->where('todoable_type', 'reservations')
                        ->where('task', $param[0]);
        });
    }

    /**
     * By payment due and optionally by it's status.
     * 
     * @param  string $due 
     * @return mixed      
     */
    public function due($due)
    {
        $param = preg_split('/\|+/', urldecode($due));

        return $this->whereHas('dues', function($payments) use($param) {

            $payments->whereHas('payment.cost', function($costs) use($param)
            {
                return $costs->where('name', $param[0]);
            });

            if(isset($param[1])) {
                switch ($param[1]) {
                    case 'overdue':
                        return $payments->overdue();
                        break;
                    case 'late':
                        return $payments->late();
                        break;
                    case 'paid':
                        return $payments->paid();
                        break;
                    case 'pending':
                        return $payments->pending();
                        break;
                    case 'extended':
                        return $payments->extended();
                        break;
                    default:
                        return $payments;
                        break;
                }
            }

            return $payments;

        });
    }

    /**
     * By arrival designation type
     * 
     * @param  String $type
     * @return Builder
     */
    public function designation($type)
    {   
        if ($type === 'none') {
            return $this->has('designation', '<', 1);
        }

        return $this->wherehas('designation', function ($designation) use ($type) {
            return $designation->where('content', 'LIKE', "%$type%");
        });
    }

    /**
     * By minimum amount raised.
     * 
     * @param  string/integer $amount
     * @return Builder
     */
    public function minAmountRaised($amount)
    {
        $amount = (int) $amount * 100; // convert to cents

        return $this->whereHas('fund', function ($fund) use ($amount) {
            return $fund->where('balance', '>=', $amount);
        });
    }

    /**
     * By maximum amount raised.
     * 
     * @param  string/integer $amount
     * @return Builder
     */
    public function maxAmountRaised($amount)
    {
        $amount = (int) $amount * 100; // convert to cents
        
        return $this->whereHas('fund', function ($fund) use ($amount) {
            return $fund->where('balance', '<=', $amount);
        });
    }

    public function minPercentRaised($percent)
    {
        $decimal = $percent / 100;

        $ids = DB::table('reservations')->join('reservation_costs', 'reservations.id', '=', 'reservation_costs.reservation_id')
                     ->join('costs', 'costs.id', '=', 'reservation_costs.cost_id')
                     ->join('funds', 'reservations.id', '=', 'funds.fundable_id')
                     ->groupBy('reservations.id')
                     ->havingRaw("funds.balance/SUM(costs.amount) >= $decimal")
                     ->selectRaw('reservations.id, funds.balance, costs.amount')
                     ->pluck('reservations.id');

        return $this->whereIn('id', $ids);
    }

    public function maxPercentRaised($percent)
    {
        $decimal = $percent / 100;

        $ids = DB::table('reservations')->join('reservation_costs', 'reservations.id', '=', 'reservation_costs.reservation_id')
            ->join('costs', 'costs.id', '=', 'reservation_costs.cost_id')
            ->join('funds', 'reservations.id', '=', 'funds.fundable_id')
            ->groupBy('reservations.id')
            ->havingRaw("funds.balance/SUM(costs.amount) <= $decimal")
            ->selectRaw('reservations.id, funds.balance, costs.amount')
            ->pluck('reservations.id');

        return $this->whereIn('id', $ids);
    }

    /**
     * Has no room for the given accomodation or plan.
     * 
     * @param  String $roomable Accommodation or Plan
     * @return Builder
     */
    public function hasRoom($roomable)
    {   
        // grab the parameters seperated by "|" pipe
        $param = preg_split('/\|+/', $roomable);

        // check for existence of value after "|" pipe
        if (isset($param[1])) {
            // query reservations that have rooms
            return $this->wherehas('rooms', function($room) use ($param) {
                // query a room in plan or accomodation
                return $room->whereHas($param[0], function($query) use($param) {
                    return $query->where('id', $param[1]);
                });
            });
        }

        // if no value exists after the "|" pipe, only use first value
        return $this->has('rooms');
    }

    /**
     * Has no room for the given accomodation or plan.
     * 
     * @param  String $roomable Accommodation or Plan
     * @return Builder
     */
    public function noRoom($roomable)
    {
        // grab the parameters seperated by "|" pipe
        $param = preg_split('/\|+/', $roomable);

        // check for existence of value after "|" pipe
        if (isset($param[1])) {
            // query reservations that have rooms
            return $this->whereDoesntHave('rooms', function($room) use ($param) {
                // query a room in plan or accomodation
                return $room->whereHas($param[0], function($query) use($param) {
                    return $query->where('id', '=', $param[1]);
                });
            });
        }

        // if no value exists after the "|" pipe, only use first value
        return $this->doesntHave('rooms');
    }

    public function inSquad()
    {
        return $this->has('squads');
    }

    public function noSquad()
    {
        return $this->has('squads', '<', 1);
    }

    public function notInTransport($transportId)
    {
        if ($transportId === 'true')
            return $this->doesntHave('transports');

        return $this->whereDoesntHave('transports', function ($transport) use ($transportId) {
            return $transport->where('transports.id', $transportId);
        });
    }

    public function inTransport($transportId)
    {
        if ($transportId === 'true')
            return $this->has('transports');

        return $this->whereHas('transports', function ($transport) use ($transportId) {
            return $transport->where('transports.id', $transportId);
        });
    }

    public function notTraveling($designation)
    {
        return $this->whereDoesntHave('transports', function ($transport) use ($designation) {
            return $transport->where('transports.designation', $designation);
        });
    }

    public function traveling($designation)
    {
        return $this->whereHas('transports', function ($transport) use ($designation) {
            return $transport->where('transports.designation', $designation);
        });
    }

    public function region($id)
    {
        return $this->whereHas('squads.team.regions', function ($region) use ($id) {
            return $region->where('id', $id);
        });
    }

    /*
     * Search Improvements
     */

    public function name($value)
    {
        $searchTerms = collect(explode(' ', $value));

        $first = $searchTerms->first();
        $last = $searchTerms->last();

        $this->when(($searchTerms->count() > 1), function ($query) use ($searchTerms, $first, $last) {
            return $query->where('surname', 'LIKE', "%$last%")->where('given_names', 'LIKE', "%$first%");
        })->when(($searchTerms->count() < 2), function ($query) use ($searchTerms, $first) {
            return $query->where('given_names', 'LIKE', "%$first%")
                ->orWhere('surname', 'LIKE', "%$first%");
        });
    }

    public function givenNames($value)
    {
        return $this->where('given_names', 'LIKE', "%$value%");
    }

    public function surname($value)
    {
        return $this->where('surname', 'LIKE', "%$value%");
    }

    public function email($value)
    {
        return $this->where('email', 'LIKE', "%$value%");
    }

    public function phone($value)
    {
        return $this->where('phone_one', 'LIKE', "%$value%")
            ->orWhere('phone_two', 'LIKE', "%$value%");;
    }

    public function phoneOne($value)
    {
        return $this->where('phone_one', 'LIKE', "%$value%");
    }

    public function phoneTwo($value)
    {
        return $this->where('phone_two', 'LIKE', "%$value%");
    }

    public function teamRole($value)
    {
        $code = TeamRole::get_code($value);

        return $this->where('desired_role', $code);
    }
}