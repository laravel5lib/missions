<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\v1\ExportRequest;
use App\Http\Requests\v1\FundRequest;
use App\Http\Transformers\v1\FundTransformer;
use App\Jobs\ExportFunds;
use App\Models\v1\Fund;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FundsController extends Controller
{
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

    /**
     * Get all funds.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $funds = $this->fund
                      ->filter($request->all())
                      ->paginate($request->get('per_page', 10));

        return $this->response->paginator($funds, new FundTransformer);
    }

    /**
     * Get a fund by it's id or slug.
     *
     * @param $param
     * @return \Dingo\Api\Http\Response
     */
    public function show($param)
    {
        $fund = $this->fund->where('id', $param)
                           ->orWhere('slug', $param)
                           ->withTrashed()
                           ->firstOrFail();

        return $this->response->item($fund, new FundTransformer);
    }

    /**
     * Create a new fund.
     *
     * @param FundRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(FundRequest $request)
    {
        $fund = $this->fund->create([
            'name' => $request->get('name'),
            'slug' => str_slug($request->get('name'), '-'),
            'balance' => $request->get('balance', 0),
            'fundable_id' => $request->get('fundable_id'),
            'fundable_type' => $request->get('fundable_type'),
            'class_id' => $request->get('class_id'),
            'item_id' => $request->get('item_id')
        ]);

        if ($request->has('tags'))
            $fund->tag($request->get('tags'));

        return $this->response->item($fund, new FundTransformer);
    }

    /**
     * Update a fund.
     *
     * @param $id
     * @param FundRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update($id, FundRequest $request)
    {
        $fund = $this->fund->findOrFail($id);

        $fund->update([
            'name' => $request->get('name', $fund->name),
            'slug' => $request->get('slug', $fund->slug),
            'balance' => $request->get('balance', $fund->balance),
            'fundable_id' => $request->get('fundable_id', $fund->fundable_id),
            'fundable_type' => $request->get('fundable_type', $fund->fundable_type),
            'class_id' => $request->get('class_id', $fund->class_id),
            'item_id' => $request->get('item_id', $fund->item_id)
        ]);

        if ($request->has('tags'))
            $fund->retag($request->get('tags'));

        return $this->response->item($fund, new FundTransformer);
    }

    /**
     * Reconcile a fund's balance.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function reconcile($id)
    {
        $fund = $this->fund->findOrFail($id);

        $fund->reconcile();

        return $this->response->item($fund, new FundTransformer);
    }

    /**
     * Delete (close) a fund.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $fund = $this->fund->findOrFail($id);

        $fund->delete();

        return $this->response->noContent();
    }

    /**
     * Export funds.
     *
     * @param ExportRequest $request
     * @return mixed
     */
    public function export(ExportRequest $request)
    {
        $this->dispatch(new ExportFunds($request->all()));

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly.'
        ]);
    }
}
