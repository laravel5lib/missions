<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;
use Dingo\Api\Exception\InternalHttpException;

class ReservationsController extends Controller
{
    use SEOTools;

    public function index()
    {
        $this->seo()->setTitle('Reservations');

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

        $locked = $reservation->trip->campaign->reservations_locked;

        $title = $reservation->name.'\'s Reservation ' . title_case(str_replace("-"," ",$tab));
        $this->seo()->setTitle($title);

        return view('dashboard.reservations.' . $tab, compact('reservation', 'rep', 'tab', 'locked'));
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
            case 'squad':
                return 'trip.group,squads.team.squads.reservations';
            default:
                return 'trip.campaign';
                break;
        }
    }
}
