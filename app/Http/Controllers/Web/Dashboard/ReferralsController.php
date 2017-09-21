<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class ReferralsController extends Controller
{
    use SEOTools;

    public function create()
    {
        $this->seo()->setTitle('Add Referral');

        return view('dashboard.referrals.create');
    }

    public function show($id)
    {
        $referral = $this->api->get('referrals/' . $id);

        $this->seo()->setTitle($referral->applicant_name . ' - Referral');

        return view('dashboard.referrals.show', compact('referral'));
    }

    public function edit($id)
    {
        $this->seo()->setTitle('Edit Referral');

        return view('dashboard.referrals.edit', compact('id'));
    }
}
