<?php

namespace App\Http\Controllers\Api;

use App\Http\Transformers\v1\DonorTransformer;
use App\Models\v1\Fund;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FundDonorsController extends Controller
{

    /**
     * @var Fund
     */
    private $fund;

    /**
     * FundDonorsController constructor.
     * @param Fund $fund
     */
    public function __construct(Fund $fund)
    {
        $this->fund = $fund;
    }

    /**
     * Get all donors.
     *
     * @param $fund_id
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index($fund_id, Request $request)
    {
        $donors = $this->fund->findOrFail($fund_id)
                        ->donors()
                        ->filter($request->all())
                        ->paginate($request->get('per_page', 10));

        return $this->response->paginator($donors, new DonorTransformer(['fund_id' => $fund_id]));
    }
}
