<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Referral;
use App\Http\Controllers\Controller;

class ReferralsController extends Controller
{
    public function create()
    {
        $this->authorize('create', Referral::class);

        return view('admin.records.referrals.create');
    }

    public function show(Referral $referral)
    {
        $this->authorize('view', $referral);

        return view('admin.records.referrals.show', compact('referral'));
    }

    public function edit(Referral $referral)
    {
        $this->authorize('update', $referral);

        return view('admin.records.referrals.edit')->with('id', $referral->id);
    }
}
