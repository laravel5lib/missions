<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
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
        $funds = $this->api->get('funds?page=' . $request->get('page', 1));

        $funds->setPath('/admin/funds');

        return view('admin.financials.funds.index', compact('funds'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $fund = $this->api->get('funds/' . $id);

        return view('admin.financials.funds.show', compact('fund'));
    }
}
