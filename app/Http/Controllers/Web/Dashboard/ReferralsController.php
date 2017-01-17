<?php

namespace App\Http\Controllers\Web\Dashboard;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReferralsController extends Controller
{
    public function create()
    {
        return view('dashboard.referrals.create');
    }
    
    public function show($id)
    {
        $referral = $this->api->get('referrals/' . $id);

        return view('dashboard.referrals.show', compact('referral'));
    }

    public function edit($id)
    {
        return view('dashboard.referrals.edit', compact('id'));
    }
}
