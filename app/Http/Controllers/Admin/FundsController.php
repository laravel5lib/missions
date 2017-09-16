<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Models\v1\Fund;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FundsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view', Fund::class);

        $funds = $this->api->get('funds?page=' . $request->get('page', 1));

        $funds->setPath('/admin/funds');

        return view('admin.financials.funds.index', compact('funds'));
    }

    /**
     * Display the specified resource.
     *
     * @param Fund $fund
     * @return \Illuminate\Http\Response
     */
    public function show(Fund $fund)
    {
        $this->authorize('view', $fund);

        return view('admin.financials.funds.show', compact('fund'));
    }
}
