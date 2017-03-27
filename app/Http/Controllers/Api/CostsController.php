<?php

namespace App\Http\Controllers\api;

use App\Models\v1\Cost;
use App\Models\v1\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Jobs\UpdateReservationCosts;
use App\Http\Requests\v1\CostRequest;
use App\Http\Transformers\v1\CostTransformer;

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

        if ($request->has('payments'))
        {
            $payments = collect($request->get('payments'));
            foreach ($payments as $payment)
            {
                $cost->payments()->create($payment);
            }
        } else {
            $cost->createDefaultPayment();
        }

        if ($cost->costAssignable instanceOf Trip) {
            $this->dispatch(new UpdateReservationCosts($cost));
        }

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
            'name' => $request->get('name', $cost->name),
            'amount' => $request->get('amount', $cost->amount),
            'description' => $request->get('description', $cost->description),
            'type' => $request->get('type', $cost->type),
            'active_at' => $request->get('active_at', $cost->active_at)
        ]);
        
        // TODO: check for changes on certain attributes and then dispatch the job.
        // reservation costs don't need to be updated for simple cost name and description changes.
        // $cost->name = $request->get('name');
        // $cost->save();
        // return $cost->getOriginal('name');

        if ($cost->costAssignable instanceOf Trip) {
            $this->dispatch(new UpdateReservationCosts($cost));
        }

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
