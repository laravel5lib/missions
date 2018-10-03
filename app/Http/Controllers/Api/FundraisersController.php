<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Http\Requests;
use App\Models\v1\Donor;
use App\Models\v1\Fundraiser;
use App\Http\Controllers\Controller;
use Dingo\Api\Contract\Http\Request;
use App\Http\Requests\v1\FundraiserRequest;
use App\Http\Transformers\v1\DonorTransformer;
use App\Http\Transformers\v1\DonationTransformer;
use App\Http\Transformers\v1\FundraiserTransformer;
use App\Http\Transformers\v1\TransactionTransformer;

class FundraisersController extends Controller
{
    protected $fundraiser;

    /**
     * Instantiate a new UsersController instance.
     *
     * @param Fundraiser $fundraiser
     */
    public function __construct(Fundraiser $fundraiser)
    {
        $this->fundraiser = $fundraiser;

        $this->middleware('api.auth', ['only' => ['store','update','destroy']]);
    }

    /**
     * Get all fundraisers.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $fundraisers = $this->fundraiser
            ->whereDoesntHave('fund', function($fund) {
                return $fund->where('fundable_type', 'projects');
            })
            ->filter($request->all())
            ->paginate($request->get('per_page', 25));

        return $this->response->paginator($fundraisers, new FundraiserTransformer);
    }

    /**
     * Get one fundraiser.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($uuid)
    {
        $fundraiser = $this->fundraiser->whereUuid($uuid)->first();

        return $this->response->item($fundraiser, new FundraiserTransformer);
    }

    /**
     * Get a list of donors.
     *
     * @param $id
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function donors($id, Request $request)
    {
        $per_page = $request->get('per_page', 10);
        $page = $request->get('page', 1);

        $fundraiser = $this->fundraiser->whereUuid($id)->first();

        $amountGivenAnonymously = $fundraiser->donations()->where('anonymous', true)->sum('amount');

        $donors = $fundraiser->donations()
                             ->where('anonymous', false)
                             ->with('donor.account.slug')
                             ->filter($request->all())
                             ->get()
                             ->groupBy('donor.id')
                             ->map(function ($donation) {
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

        $donors = $donors->sortByDesc('total_donated');

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

    /**
     * Get a list of donations.
     *
     * @param $id
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function donations($id, Request $request)
    {
        $fundraiser = $this->fundraiser->whereUuid($id)->first();

        $donations = $fundraiser->donations()
            ->filter($request->all())
            ->latest()
            ->paginate($request->get('per_page', 10));

        return $this->response->paginator($donations, new DonationTransformer);
    }

    /**
     * Create a new fundraiser and save in storage.
     *
     * @param FundraiserRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(FundraiserRequest $request)
    {
        $fundraiser = $this->fundraiser->create($request->all());

        $fundraiser->slug()->create(['url' => $request->get('url')]);

        return $this->response->item($fundraiser, new FundraiserTransformer);
    }

    /**
     * Update a specific fundraiser in storage.
     *
     * @param FundraiserRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(FundraiserRequest $request, $id)
    {
        $fundraiser = $this->fundraiser->whereUuid($id)->first();

        $fundraiser->update([
            'name'             => $request->get('name', $fundraiser->name),
            'short_desc'       => $request->get('short_desc', $fundraiser->short_desc),
            'featured_image_id' => $request->get('featured_image_id', $fundraiser->featured_image_id),
            'type'             => $request->get('type', $fundraiser->type),
            'fund_id'          => $request->get('fund_id', $fundraiser->fund_id),
            'public'           => $request->get('public', $fundraiser->public),
            'description'      => $request->get('description', $fundraiser->description),
            'show_donors'      => $request->get('show_donors', $fundraiser->show_donors),
            'started_at'       => $request->get('started_at', $fundraiser->started_at),
            'ended_at'         => $request->get('ended_at', $fundraiser->ended_at)
        ]);

        $fundraiser->slug()->update(['url' => $request->get('url', $fundraiser->url)]);
        $fundraiser->load(['slug']);

        return $this->response->item($fundraiser, new FundraiserTransformer);
    }

    /**
     * Delete a specific fundraiser from storage.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $fundraiser = $this->fundraiser->whereUuid($id)->first();

        $fundraiser->delete();

        return $this->response->noContent();
    }
}
