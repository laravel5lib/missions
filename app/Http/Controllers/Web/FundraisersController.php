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
     * Get the fundraiser page by it's url slug.
     *
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($slug)
    {
        $fundraiser = $this->api->get('/fundraisers?url='.$slug);

        $fundraiser = $fundraiser->shift();

        return view('site.fundraisers.show', compact('fundraiser'));
    }
}
