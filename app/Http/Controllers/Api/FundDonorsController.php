<?php

namespace App\Http\Controllers\Api;

use Ramsey\Uuid\Uuid;
use App\Models\v1\Fund;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Transformers\v1\DonorTransformer;

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
        $per_page = $request->get('per_page', 10);
        $page = $request->get('page', 1);

        $fund = $this->fund->findOrFail($fund_id);

        $amountGivenAnonymously = $fund->donations()->where('anonymous', true)->sum('amount');

        $donors = $fund->donations()
                             ->where('anonymous', false)
                             ->with('donor.account.slug')
                             ->filter($request->all())
                             ->get()
                             ->groupBy('donor.id')
                             ->map(function($donation) {
                                return [
                                    'name' => $donation->pluck('donor.name')->first(),
                                    'total_donated' => $donation->sum('amount')/100,
                                    'account_url' => $donation->pluck('donor.account.slug.url')->first()
                                ];
                             });

        if ($amountGivenAnonymously > 0) {
            $donors->put((string) Uuid::uuid4(), [
                'name' => 'Anonymous',
                'total_donated' => $amountGivenAnonymously > 0 ? $amountGivenAnonymously/100 : 0,
                'account_url' => null
            ]);
        }
        
        $donors->sortByDesc('total_donated');

        $count = $donors->count();

        $donors = $donors->forPage($page, $per_page)->all();

       return [
        'data' => $donors, 
        'meta' => [
                'pagination' => [
                    "total" => (int) $count,
                    "count" => (int) $count,
                    "per_page" => (int) $per_page,
                    "current_page" => (int) $page,
                    "total_pages" => (int) ceil($count / $per_page),
                    "links" => []
                ]
            ]
        ];
    }
}
