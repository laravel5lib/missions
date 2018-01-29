<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Campaign;
use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use App\Models\v1\TripInterest;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\SEOMeta;

class AdminController extends Controller
{
    public function index()
    {
        SEOMeta::setTitle('Admin Dashboard');

        $campaigns = Campaign::active()->get();
        $interests = TripInterest::with('trip.campaign')->latest()->take(5)->get();
        $reservations = Reservation::current()->latest()->take(5)->get();

        return view('admin.index', compact('campaigns', 'interests', 'reservations'));
    }
}
