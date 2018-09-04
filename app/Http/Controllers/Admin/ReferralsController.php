<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Referral;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class ReferralsController extends Controller
{
    use SEOTools;

    public function create()
    {
        $this->authorize('create', Referral::class);

        $this->seo()->setTitle('Add Referral');

        return view('admin.records.referrals.create');
    }

    public function edit(Referral $referral)
    {
        $this->authorize('update', $referral);

        $this->seo()->setTitle('Edit Referral');

        return view('admin.records.referrals.edit')->with('id', $referral->id);
    }
}
