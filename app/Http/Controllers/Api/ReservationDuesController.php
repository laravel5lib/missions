<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\v1\Reservation;
use App\Http\Transformers\v1\DueTransformer;
use App\Http\Requests\v1\DueRequest;

class ReservationDuesController extends Controller
{
    protected $reservation;

    function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;    
    }

    /**
     * Get all a reservation's dues.
     * 
     * @param  string  $reservation_id
     * @param  Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index($reservation_id, Request $request)
    {
        $dues = $this->reservation
                     ->findOrFail($reservation_id)
                     ->dues()
                     ->filter($request->all())
                     ->paginate($request->get('per_page', 25));

        return $this->response->paginator($dues, new DueTransformer);
    }

    /**
     * Get a specific reservation due.
     * 
     * @param  string $reservation_id
     * @param  string $due_id
     * @return \Dingo\Api\Http\Response
     */
    public function show($reservation_id, $due_id)
    {
        $due = $this->reservation
                    ->findOrFail($reservation_id)
                    ->dues()
                    ->findOrFail($due_id);

        return $this->response->item($due, new DueTransformer);
    }

    /**
     * Update a reservation's due.
     * @param  string     $reservation_id
     * @param  string     $due_id
     * @param  DueRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update($reservation_id, $due_id, DueRequest $request)
    {
        $due = $this->reservation
                     ->findOrFail($reservation_id)
                     ->dues()
                     ->findOrFail($due_id);

        $due->update([
            'due_at' => $request->get('due_at', $due->due_at),
            'grace_period' => $request->get('grace_period', $due->grace_period)
        ]);

        return $this->response->item($due, new DueTransformer);
    }
}
