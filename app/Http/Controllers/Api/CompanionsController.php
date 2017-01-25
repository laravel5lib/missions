<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\CompanionRequest;
use App\Http\Transformers\v1\ReservationTransformer;

class CompanionsController extends Controller
{
    private $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get all companions
     * 
     * @param  String $reservation_id
     * @return response
     */
    public function index($reservation_id)
    {
        $reservation = $this->reservation->findOrFail($reservation_id);

        return $this->response->collection($reservation->companions, new ReservationTransformer);
    }

    /**
     * Add a companion
     * 
     * @param  String $reservation_id
     * @return response
     */
    public function store($reservation_id, CompanionRequest $request)
    {
        $this->set_companion_relationship($reservation_id, $request);

        $this->set_reverse_relationship($reservation_id, $request);

        return $this->response->created();
    }

    /**
     * Remove a companion
     * 
     * @param  String $reservation_id
     * @param  String $companion_reservation_id
     * @return response
     */
    public function destroy($reservation_id, $companion_reservation_id)
    {
        $this->remove_companion_relationship($reservation_id, $companion_reservation_id);

        $this->remove_reverse_relationship($reservation_id, $companion_reservation_id);

        return $this->response->noContent();
    }

    private function set_companion_relationship($reservation_id, $request)
    {
        $reservation = $this->reservation->findOrFail($reservation_id);

        $reservation->companions()
                    ->attach($request->get('companion_reservation_id'), [
                        'relationship' => $request->get('relationship')
                    ]);
    }

    private function set_reverse_relationship($reservation_id, $request)
    {
        $companion = $this->reservation->findOrFail($request->get('companion_reservation_id'));

        $relationship = $this->get_reciprocal_relationship($request->get('relationship'));

        $companion->companions()
                  ->attach($reservation_id, [
                    'relationship' => $relationship
                  ]);
    }

    private function get_reciprocal_relationship($relationship)
    {
        switch ($relationship) {
            case 'guardian':
                return 'dependent';
                break;
            case 'dependent':
                return 'guardian';
                break;
            default:
                return $relationship;
                break;
        }
    }

    private function remove_companion_relationship($reservation_id, $companion_reservation_id)
    {
        $reservation = $this->reservation->findOrFail($reservation_id);

        $reservation->companions()->detach($companion_reservation_id);
    }

    private function remove_reverse_relationship($reservation_id, $companion_reservation_id)
    {
        $companion = $this->reservation->findOrFail($companion_reservation_id);

        $companion->companions()->detach($reservation_id);
    }
}
