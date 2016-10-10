<?php

namespace App\Http\Controllers\Web;

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
        } catch (InternalHttpException $e) {
            $response = $e->getResponse();

            return $response;
        }

        return view('dashboard.reservations.' . $tab, compact('reservation', 'tab'));
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

//Route::get('/reservations/{id}/{tab?}', function ($id, $tab = 'details') use ($dispatcher) {
//    try {
//        $reservation = $dispatcher->get('reservations/' . $id, ['include' => 'trip.campaign']);
//    } catch (Dingo\Api\Exception\InternalHttpException $e) {
//        // We can get the response here to check the status code of the error or response body.
//        $response = $e->getResponse();
//
//        return $response;
//    }
//    $active = 'details';
//    return view('dashboard.reservations.show', compact('reservation', 'active'));
//});
//
//Route::get('/reservations/{id}/requirements', function ($id, Request $request) use ($dispatcher) {
//    try {
//        $reservation = $dispatcher->get('reservations/' . $id, ['include' => 'trip.campaign,requirements']);
//    } catch (Dingo\Api\Exception\InternalHttpException $e) {
//        // We can get the response here to check the status code of the error or response body.
//        $response = $e->getResponse();
//
//        return $response;
//    }
//    $active = $request->segment(4);
//    return view('dashboard.reservations.requirements', compact('reservation', 'active'));
//});
//
//Route::get('/reservations/{id}/funding', function ($id, Request $request) use ($dispatcher) {
//    try {
//        $reservation = $dispatcher->get('reservations/' . $id, ['include' => 'trip.campaign,fundraisers,costs.payments']);
//    } catch (Dingo\Api\Exception\InternalHttpException $e) {
//        // We can get the response here to check the status code of the error or response body.
//        $response = $e->getResponse();
//
//        return $response;
//    }
//
//    // Calculate total due
//    $totalAmountDue = 0;
//    foreach ($reservation->costs as $cost) {
//        $totalAmountDue += $cost->amount;
//        if($cost->type === 'incremental') {
//            foreach ($cost->payments as $payment) {
//                if($payment->due_at->gt(now())){
//                    $payment->due_next = true;
//                    break;
//                }
//            }
//        }
//    }
//    // Calculate total raised
//    $totalAmountRaised = 0;
//    foreach($reservation->fundraisers as $fundraiser) {
//        $totalAmountRaised = $fundraiser->raised() / 100;
//    }
//
//    $active = $request->segment(4);
//    return view('dashboard.reservations.funding', compact('reservation', 'totalAmountDue', 'totalAmountRaised', 'active'));
//});
//
//Route::get('/reservations/{id}/funding/{fid}/donations', function ($id, $fid, Request $request) use ($dispatcher) {
//    try {
//        $reservation = $dispatcher->get('reservations/' . $id, ['include' => 'trip.campaign']);
//        $fundraiser = $dispatcher->get('fundraisers/' . $fid);
//        //        $donations = $fundraiser->donations();
//        //        return response()->json($fundraiser);
//    } catch (Dingo\Api\Exception\InternalHttpException $e) {
//        // We can get the response here to check the status code of the error or response body.
//        $response = $e->getResponse();
//
//        return $response;
//    }
//    $active = $request->segment(4);
//    return view('dashboard.reservations.donations', compact('reservation', 'fundraiser', 'active'));
//});
//
//Route::get('/reservations/{id}/deadlines', function ($id, Request $request) use ($dispatcher) {
//    try {
//        $reservation = $dispatcher->get('reservations/' . $id, ['include' => 'trip.campaign,deadlines,requirements,costs.payments']);
//
//        // Pluck payments
//        $payments = [];
//        foreach ($reservation->costs as $cost) {
//            foreach ($cost->payments as $payment) {
//                $payment->cost_name = $cost->name;
//                $payments[] = $payment;
//            }
//        }
//        //return response()->json($payments);
//        // add 'due_at' property to deadlines just as payments and requirements
//        $deadlines = [];
//        foreach ($reservation->deadlines as $key => $deadline) {
//            $deadline->due_at = $deadline->date->toDateTimeString();
//            $deadlines[] = $deadline;
//        }
//
//        $requirements = $reservation->requirements->toArray();
//
//        $merged_deadlines = array_merge($deadlines, $requirements, $payments);
//        // Sort merged deadlines by due_at property
//        usort($merged_deadlines, function($a, $b) {
//            $ad = carbon($a['due_at']);
//            $bd = carbon($b['due_at']);
//
//            if ($ad->eq($bd)) {
//                return 0;
//            }
//
//            return $ad->lt($bd) ? -1 : 1;
//        });
//        $all_deadlines = collect($merged_deadlines);
//
//    } catch (Dingo\Api\Exception\InternalHttpException $e) {
//        // We can get the response here to check the status code of the error or response body.
//        $response = $e->getResponse();
//
//        return $response;
//    }
//    $active = $request->segment(4);
//    return view('dashboard.reservations.deadlines', compact('reservation', 'active', 'all_deadlines'));
//});
}
