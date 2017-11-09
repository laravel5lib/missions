<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class DonationsController extends Controller
{
    use SEOTools;

    public function show($recipient = null)
    {
        // redirect if using an old donation route
        if (request('type')) {
            return redirect('/fundraisers');
        }

        $fund = !is_null($recipient) ?
                  $this->api->get('funds/' . $recipient) :
                  $this->api->get('funds/general');

        $recipient = $fund->name;
        $type = $fund->type;
        $slug = $fund->slug;
        $id = $fund->id;

        $this->seo()->setTitle('Donate to ' . $fund->name);

        if ($fund->slug === 'general') {
            return view('site.donate-year-end', compact('recipient', 'type', 'slug', 'id'));
        }

        return view('site.donate', compact('recipient', 'type', 'slug', 'id'));
    }
}
