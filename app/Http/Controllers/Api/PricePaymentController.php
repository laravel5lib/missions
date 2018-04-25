<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Price;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;

class PricePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($priceId)
    {
        $payments = Price::whereUuid($priceId)
            ->firstOrFail()
            ->payments()
            ->paginate();
        
        return PaymentResource::collection($payments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $priceId)
    {
        Price::whereUuid($priceId)
            ->firstOrFail()
            ->payments()
            ->create($request->all());

        return response()->json(['message' => 'New payment added.'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($priceId, $paymentId)
    {
        $payment = Price::whereUuid($priceId)
            ->firstOrFail()
            ->payments()
            ->whereUuid($paymentId)
            ->firstOrFail();

        return new PaymentResource($payment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
