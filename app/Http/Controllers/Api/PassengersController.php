<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\PassengerRequest;
use App\Http\Transformers\v1\PassengerTransformer;
use App\Models\v1\Passenger;
use Illuminate\Http\Request;

use App\Http\Requests;

class PassengersController extends Controller
{

    /**
     * @var Passenger
     */
    private $passenger;

    /**
     * PassengersController constructor.
     * @param Passenger $passenger
     */
    public function __construct(Passenger $passenger)
    {
        $this->passenger = $passenger;
    }

    /**
     * Get a list of passengers
     *
     * @param $transportId
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index($transportId, Request $request)
    {
        $passengers = $this->passenger
                           ->where('transport_id', $transportId)
                           ->filter($request->all())
                           ->paginate($request->get('per_page', 10));

        return $this->response->paginator($passengers, new PassengerTransformer);
    }

    /**
     * Create a new passenger
     *
     * @param $transportId
     * @param PassengerRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store($transportId, PassengerRequest $request)
    {
        $passenger = $this->passenger->firstOrCreate([
            'transport_id' => $transportId,
            'reservation_id' => $request->get('reservation_id')
        ]);

        return $this->response->item($passenger, new PassengerTransformer);
    }

    /**
     * Get the specified passenger
     *
     * @param $transportId
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($transportId, $id)
    {
        $passenger = $this->passenger
                          ->where('transport_id', $transportId)
                          ->findOrFail($id);

        return $this->response->item($passenger, new PassengerTransformer);
    }

    /**
     * Update the specified passenger
     *
     * @param $transportId
     * @param $id
     * @param PassengerRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update($transportId, $id, PassengerRequest $request)
    {
        $passenger = $this->passenger
                          ->where('transport_id', $transportId)
                          ->findOrFail($id);

        $passenger->update($request->all());

        return $this->response->item($passenger, new PassengerTransformer);
    }

    /**
     * Delete the specified passenger
     *
     * @param $transportId
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($transportId, $id)
    {
        $passenger = $this->passenger
            ->where('transport_id', $transportId)
            ->findOrFail($id);

        $passenger->delete();

        return $this->response->noContent();
    }
}
