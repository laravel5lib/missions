<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Models\v1\Donor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DonorsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view', Donor::class);

        $donors = $this->api->get('donors?page=' . $request->get('page', 1));

        $donors->setPath('/admin/donors');

        return view('admin.financials.donors.index', compact('donors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Donor::class);

        return view('admin.financials.donors.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  Donor $donor
     * @return \Illuminate\Http\Response
     */
    public function show(Donor $donor)
    {
        $this->authorize('view', $donor);

        return view('admin.financials.donors.show', compact('donor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Donor $donor
     * @return \Illuminate\Http\Response
     */
    public function edit(Donor $donor)
    {
        $this->authorize('update', $donor);

        return view('admin.financials.donors.edit')->with('id', $donor->id);
    }
}
