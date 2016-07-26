<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Reservation;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReservationsController extends Controller
{
    public function index()
    {
        return view('admin.reservations.index');
    }

    public function show($id)
    {
        $reservation = $this->api->get('reservations/'.$id, ['include' => '']);

        return view('admin.reservations.show')->with('reservation', $reservation);
    }

    public function edit($reservationId)
    {

        return view('admin.reservations.edit')->with('reservationId', $reservationId);
    }

    public function create($campaignId)
    {
        // $campaign = Reservation::whereId($campaignId)->orWhere('page_url', $campaignId)->first();
        // $this->api->get('campaigns/'.$campaignId);
        // return view('admin.reservations.create')->with('campaign', $campaign);
    }}
