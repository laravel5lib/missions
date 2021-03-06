<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Donor;
use App\Models\v1\Group;
use App\Models\v1\Campaign;
use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use App\Models\v1\Transaction;
use App\Models\v1\TripInterest;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\SEOMeta;

class AdminController extends Controller
{
    public function index()
    {
        SEOMeta::setTitle('Admin Dashboard');
        $users = User::count();
        $reservations = Reservation::current()->count();
        $interests = TripInterest::where('status', 'undecided')->current()->count();
        $groups = Group::where('status', 'approved')->count();
        $donations = Transaction::where('type', 'donation')->count();
        $donors = Donor::count();
        $activeCampaigns = Campaign::where('ended_at', '>', now())->get();

        return view('admin.index', compact('users', 'reservations', 'groups', 'interests', 'donations', 'donors', 'activeCampaigns'));
    }
}
