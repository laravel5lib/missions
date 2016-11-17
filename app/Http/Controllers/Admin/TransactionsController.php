<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class TransactionsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $transactions = $this->api->get('transactions?page=' . $request->get('page', 1));

        $transactions->setPath('/admin/transactions');

        return view('admin.financials.transactions.index', compact('transactions'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = $this->api->get('transactions/' . $id);

        return view('admin.financials.transactions.show', compact('transaction'));
    }
}
