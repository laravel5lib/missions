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

        $this->seo()->setTitle('Transactions');

        return view('admin.financials.transactions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);

        $this->authorize('view', $transaction);

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
        $transaction = Transaction::findOrFail($id);

        $this->authorize('view', $transaction);

        $this->seo()->setTitle('Edit Transaction');

        return view('admin.financials.transactions.edit', compact('transaction'));
    }
}
