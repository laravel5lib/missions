<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\v1\Transaction;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class TransactionsController extends Controller
{
    use SEOTools;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view', Transaction::class);

        $transactions = $this->api->get('transactions?page=' . $request->get('page', 1));

        $transactions->setPath('/admin/transactions');

        $this->seo()->setTitle('Transactions');

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
        $this->authorize('view', Transaction::class);

        $transaction = $this->api->get('transactions/' . $id);

        $this->seo()->setTitle('Transaction Details');

        return view('admin.financials.transactions.show', compact('transaction'));
    }

    /**
     * Edit the specified resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $this->authorize('view', Transaction::class);

        $transaction = $this->api->get('transactions/' . $id);

        $this->seo()->setTitle('Edit Transaction');

        return view('admin.financials.transactions.edit', compact('transaction'));
    }
}
