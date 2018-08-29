<?php

namespace App\Http\Controllers\api;

use App\Models\v1\Cost;
use App\Http\Controllers\Controller;
use Dingo\Api\Contract\Http\Request;
use App\Http\Requests\v1\PaymentRequest;
use App\Http\Transformers\v1\PaymentTransformer;

class CostPaymentsController extends Controller
{

    /**
     * @var Cost
     */
    private $cost;

    /**
     * CostPaymentsController constructor.
     * @param Cost $cost
     */
    public function __construct(Cost $cost)
    {
        $this->cost = $cost;
    }

    /**
     * Get all of the cost's payments.
     *
     * @param Request $request
     * @param $costId
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request, $costId)
    {
        $payments = $this->cost
            ->findOrFail($costId)
            ->payments()
            ->filter($request->all())
            ->get();

        return $this->response->collection($payments, new PaymentTransformer);
    }

    /**
     * Get a payment.
     *
     * @param $costId
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($costId, $id)
    {
        $payment = $this->cost
            ->findOrFail($costId)
            ->payments()
            ->findOrFail($id);

        return $this->response->item($payment, new PaymentTransformer);
    }

    /**
     * Create a new payment.
     *
     * @param PaymentRequest $request
     * @param $costId
     * @return \Dingo\Api\Http\Response
     */
    public function store(PaymentRequest $request, $costId)
    {
        $cost = $this->cost->findOrFail($costId);

        $payment = $cost->payments()->create([
            'amount_owed' => $request->get('amount_owed'),
            'percent_owed' => $request->get('percent_owed'),
            'due_at' => $request->get('due_at', null),
            'upfront' => $request->get('upfront', false),
            'grace_period' => $request->get('grace_period', 0)
        ]);

        return $this->response->item($payment, new PaymentTransformer);
    }

    /**
     * Update a payment.
     *
     * @param PaymentRequest $request
     * @param $costId
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(PaymentRequest $request, $costId, $id)
    {
        $payment = $this->cost->findOrFail($costId)->payments()->findOrFail($id);

        $payment->update([
            'cost_id' => $request->get('cost_id', $costId),
            'amount_owed' => $request->get('amount_owed'),
            'percent_owed' => $request->get('percent_owed'),
            'due_at' => $request->get('due_at', null),
            'upfront' => $request->get('upfront', false),
            'grace_period' => $request->get('grace_period', 0)
        ]);

        return $this->response->item($payment, new PaymentTransformer);
    }

    /**
     * Delete a payment.
     *
     * @param $costId
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($costId, $id)
    {
        $payment = $this->cost
                        ->findOrFail($costId)
                        ->payments()
                        ->findOrFail($id);

        $payment->delete();

        // need sync payments on all associated reservations.

        return $this->response->noContent();
    }
}
