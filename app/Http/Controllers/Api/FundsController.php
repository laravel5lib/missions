<?php

namespace App\Http\Controllers\Api;

use App\Http\Transformers\v1\FundTransformer;
use App\Models\v1\Fund;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FundsController extends Controller
{

    // Funds can be held for future trips
    // Deposits can never be transferred!

    /**
     * @var Fund
     */
    private $fund;

    /**
     * FundsController constructor.
     * @param Fund $fund
     */
    public function __construct(Fund $fund)
    {
        $this->fund = $fund;
    }

    public function index(Request $request)
    {
        $funds = $this->fund->filter($request->all())->paginate($request->get('per_page', 10));

        return $this->response->paginator($funds, new FundTransformer);
    }

    public function show($id)
    {
        $fund = $this->fund->findOrFail($id);

        return $this->response->item($fund, new FundTransformer);
    }

    public function reconcile($id)
    {
        $fund = $this->fund->findOrFail($id);

        $fund->reconcile();

        return $this->response->item($fund, new FundTransformer);
    }
}
