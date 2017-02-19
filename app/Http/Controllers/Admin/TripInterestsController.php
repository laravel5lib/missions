<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

class TripInterestsController extends Controller
{

    public function show($id)
    {
        $interest = $this->api->get('interests/'.$id, ['include' => 'trip']);

        return view('admin.trips.interests.show')->with(compact('interest'));
    }

    public function edit($tripId)
    {
        return view('admin.trips.interests.edit')->with('tripId', $tripId);
    }
}
