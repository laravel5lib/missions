<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DonationsController extends Controller
{
    public function show($recipient = null)
    {
        $fund = !is_null($recipient) ? 
                  $this->api->get('funds/' . $recipient) : 
                  $this->api->get('funds/missions-me');

        $recipient = $fund->name;
        $type = $fund->type;
        $slug = $fund->slug;

        return view('site.donate', compact('recipient', 'type', 'slug'));
    }
}
