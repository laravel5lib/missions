<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class FundraisersController extends Controller
{

    /**
     * Get all fundraisers.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $fundraisers = $this->api->get('fundraisers');

        return view('site.fundraisers.index', compact('fundraisers'));
    }

    /**
     * Get the fundraiser page by it's url and it's sponsor's url.
     *
     * @param $sponsor_slug
     * @param $fundraiser_slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($sponsor_slug, $fundraiser_slug)
    {
        $fundraiser = $this->api->get('fundraisers', [
            'sponsor' => $sponsor_slug,
            'url' => $fundraiser_slug
        ])->first();

        if (! $fundraiser) abort(404);

        return view('site.fundraisers.show', compact('fundraiser'));
    }
}
