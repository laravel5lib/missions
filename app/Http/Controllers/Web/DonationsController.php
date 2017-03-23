<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DonationsController extends Controller
{
    public function show($recipient = null)
    {   
        // redirect if using an old donation route
        if ( request('type') ) return redirect('/fundraisers');

        $fund = !is_null($recipient) ? 
                  $this->api->get('funds/' . $recipient) : 
                  $this->api->get('funds/general');

        $recipient = $fund->name;
        $type = $fund->type;
        $slug = $fund->slug;
        $id = $fund->id;

        return view('site.donate', compact('recipient', 'type', 'slug', 'id'));
    }
}
