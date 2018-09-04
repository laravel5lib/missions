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

    public function edit($id)
    {
        $this->seo()->setTitle('Edit Referral');

        return view('dashboard.referrals.edit', compact('id'));
    }
}
