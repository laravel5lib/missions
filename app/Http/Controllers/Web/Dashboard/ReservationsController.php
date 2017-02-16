<?php

namespace App\Http\Controllers\Web\Dashboard;

use Dingo\Api\Exception\InternalHttpException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReservationsController extends Controller
{
    public function index()
    {
        return view('dashboard.reservations.index');
    }

    public function show($id, $tab = 'details')
    {
        $include = $this->getDataToInclude($tab);

        try {
            $reservation = $this->api->get('reservations/' . $id, ['include' => $include]);
            $rep = $reservation->rep ? $reservation->rep : $reservation->trip->rep;
        } catch (InternalHttpException $e) {
            $response = $e->getResponse();

            return $response;
        }

        return view('dashboard.reservations.' . $tab, compact('reservation', 'rep', 'tab'));
    }

    private function getDataToInclude($tab)
    {
        switch ($tab) {
            case 'requirements':
                return 'trip.campaign,requirements';
                break;
            case 'funding':
                return 'trip.campaign,fundraisers,costs.payments';
                break;
            case 'deadlines':
                return 'trip.campaign,deadlines,requirements,costs.payments';
                break;
            default:
                return 'trip.campaign';
                break;
        }
    }
}
