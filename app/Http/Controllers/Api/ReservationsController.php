<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\v1\Reservation;
use Spatie\QueryBuilder\Filter;
use App\Jobs\ExportReservations;
use App\Events\RegisteredForTrip;
use Silber\Bouncer\Database\Role;
use App\Http\Controllers\Controller;
use Dingo\Api\Contract\Http\Request;
use App\Events\ReservationWasCreated;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\v1\ExportRequest;
use App\Http\Resources\ReservationResource;
use App\Http\Requests\v1\RequirementRequest;
use App\Http\Requests\v1\ReservationRequest;
use App\Http\Transformers\v1\RequirementTransformer;
use App\Http\Transformers\v1\ReservationTransformer;

class ReservationsController extends Controller
{

    /**
     * @var Reservation
     */
    private $reservation;

    /**
     * Instantiate a new UsersController instance.
     * @param Reservation $reservation
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }


    /**
     * Show all reservations.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $reservations = QueryBuilder::for(Reservation::class)
            ->allowedFilters([
                'surname', 'given_names', 'email', 'phone_one', 'phone_two',
                'address', 'city', 'zip', 'state', 'trip_id',
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
                Filter::scope('funnel')
            ])
            ->allowedIncludes(['trip.group', 'passport', 'requirements'])
            ->paginate(25);
        
        return ReservationResource::collection($reservations);
    }

    /**
     * Show the specified reservation.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $reservation = $this->reservation->withTrashed()->findOrFail($id);

        return $this->response->item($reservation, new ReservationTransformer);
    }

    /**
     * Store a reservation.
     *
     * @param ReservationRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(ReservationRequest $request)
    {
        $reservation = $this->reservation->create([
            'given_names' => trim($request->get('given_names')),
            'surname' => trim($request->get('surname')),
            'gender' => $request->get('gender'),
            'status' => $request->get('status'),
            'shirt_size' => $request->get('shirt_size'),
            'birthday' => $request->get('birthday'),
            'phone_one' => stripPhone($request->get('phone_one')),
            'phone_two' => stripPhone($request->get('phone_two')),
            'address' => trim(ucwords(strtolower($request->get('address')))),
            'city' => trim(ucwords(strtolower($request->get('city')))),
            'state' => trim(ucwords(strtolower($request->get('state')))),
            'zip' => trim(strtoupper($request->get('zip'))),
            'country_code' => $request->get('country_code'),
            'user_id' => $request->get('user_id'),
            'email' => trim(strtolower($request->get('email'))),
            'desired_role' => $request->get('desired_role'),
            'shirt_size' => $request->get('shirt_size'),
            'avatar_upload_id' => $request->get('avatar_upload_id'),
            'trip_id' => $request->get('trip_id')
        ]);

        event(new RegisteredForTrip($reservation, $request));

        return $this->response->item($reservation, new ReservationTransformer);
    }

    /**
     * Update the specified reservation.
     *
     * @param ReservationRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(ReservationRequest $request, $id)
    {   
        $reservation = $this->reservation->withTrashed()->findOrFail($id);

        $reservation->update([
            'given_names' => trim($request->get('given_names', $reservation->given_names)),
            'surname' => trim($request->get('surname', $reservation->surname)),
            'gender' => $request->get('gender', $reservation->gender),
            'status' => $request->get('status', $reservation->status),
            'shirt_size' => $request->get('shirt_size', $reservation->shirt_size),
            'birthday' => $request->get('birthday', $reservation->birthday),
            'phone_one' => stripPhone($request->get('phone_one', $reservation->phone_one)),
            'phone_two' => stripPhone($request->get('phone_two', $reservation->phone_two)),
            'address' => trim(ucwords(strtolower($request->get('address', $reservation->address)))),
            'city' => trim(ucwords(strtolower($request->get('city', $reservation->city)))),
            'state' => trim(ucwords(strtolower($request->get('state', $reservation->state)))),
            'zip' => trim(strtoupper($request->get('zip', $reservation->zip))),
            'country_code' => $request->get('country_code', $reservation->country_code),
            'user_id' => $request->get('user_id', $reservation->user_id),
            'email' => trim(strtolower($request->get('email', $reservation->email))),
            'desired_role' => $request->get('desired_role', $reservation->desired_role),
            'shirt_size' => $request->get('shirt_size', $reservation->shirt_size),
            'companion_limit' => $request->get('companion_limit', $reservation->companion_limit),
            'trip_id' => $request->get('trip_id', $reservation->trip_id)
        ]);

        $reservation->syncRequirements($request->get('requirements'));
        $reservation->syncDeadlines($request->get('deadlines'));

        return $this->response->item($reservation, new ReservationTransformer);
    }

    /**
     * Delete the specified reservation.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $reservation = $this->reservation->findOrFail($id);

        $reservation->drop();

        return $this->response->noContent();
    }

    /**
     * Restore the specified reservation.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function restore($id)
    {
        $reservation = $this->reservation->withTrashed()->findOrFail($id);

        $reservation->undoDrop();
    }

      

    public function reconcile($id)
    {
        $reservation = $this->reservation->findOrFail($id);

        $reservation->payments()->reconcile();

        return $this->response->item($reservation, new ReservationTransformer, ['include' => 'dues']);
    }

    /**
     * Update an existing requirement.
     *
     * @param $reservation_id
     * @param $requirement_id
     * @param RequirementRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function updateRequirement($reservation_id, $requirement_id, RequirementRequest $request)
    {
        $reservation = $this->reservation->findOrFail($reservation_id);

        $attributes = [
            'status' => $request->get('status', 'incomplete'),
            'grace_period' => $request->get('grace_period', null)
        ];

        $reservation->requirements()->updateExistingPivot($requirement_id, $attributes);

        return $this->response->item(
            $reservation->requirements()
            ->where('requirement_id', $requirement_id)
            ->first(),
            new RequirementTransformer
        );
    }

    /**
     * Export Reservations.
     *
     * @param ExportRequest $request
     * @return mixed
     */
    public function export(ExportRequest $request)
    {
        $this->dispatch(new ExportReservations($request->all()));

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly.'
        ]);
    }
}
