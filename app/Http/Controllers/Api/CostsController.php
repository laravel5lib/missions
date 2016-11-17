<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\v1\CostRequest;
use App\Http\Transformers\v1\CostTransformer;
use App\Models\v1\Cost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CostsController extends Controller
{
    /**
     * @var Cost
     */
    private $cost;

    /**
     * TripCostsController constructor.
     * @param Cost $cost
     */
    public function __construct(Cost $cost)
    {
        $this->cost = $cost;
    }

    /**
     * Get all costs.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $costs = $this->cost
                      ->filter($request->all())
                      ->paginate($request->get('per_page', 10));

        return $this->response->paginator($costs, new CostTransformer);
    }

    /**
     * Get one cost by id.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $cost = $this->cost->findOrFail($id);

        return $this->response->item($cost, new CostTransformer);
    }

    /**
     * Create a new Cost.
     *
     * @param CostRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(CostRequest $request)
    {
        $cost = $this->cost->create([
            'cost_assignable_type' => $request->get('cost_assignable_type'),
            'cost_assignable_id' => $request->get('cost_assignable_id'),
            'name' => $request->get('name'),
            'amount' => $request->get('amount'),
            'description' => $request->get('description'),
            'type' => $request->get('type'),
            'active_at' => $request->get('active_at')
        ]);

        return $this->response->item($cost, new CostTransformer);
    }

    /**
     * Create a new Cost.
     *
     * @param CostRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(CostRequest $request, $id)
    {
        $cost = $this->cost->findOrFail($id);

        $cost->update([
            'cost_assignable_type' => $request->get('cost_assignable_type', $cost->cost_assignable_type),
            'cost_assignable_id' => $request->get('cost_assignable_id', $cost->cost_assignable_id),
            'name' => $request->get('name'),
            'amount' => $request->get('amount'),
            'description' => $request->get('description'),
            'type' => $request->get('type'),
            'active_at' => $request->get('active_at')
        ]);

        return $this->response->item($cost, new CostTransformer);
    }

    /**
     * Delete a cost and all of it's payments.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $cost = $this->cost->findOrFail($id);

        $cost->payments()->delete();
        $cost->delete();

        // need sync payments on all associated reservations.

        return $this->response->noContent();
    }
}
