<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Trip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Transformers\v1\TripTransformer;

class TripDuplicateController extends Controller
{
    public function store($id)
    {
        $trip = Trip::findOrFail($id);

        $new = $trip->replicate(['id', 'created_at', 'updated_at']);
        $new->save();

        $duplicate = Trip::findOrFail($new->id);

        $trip->load('costs.payments','deadlines', 'requirements');

        $trip->costs->each(function($cost) use ($duplicate) {
            $newCost = $duplicate->costs()->create(
                array_except($cost->toArray(), ['cost_assignable_id', 'cost_assignable_type', 'payments'])
            );

            $cost->payments->each(function($payment) use($newCost) {
                $newCost->payments()->create(
                    array_except($payment->toArray(), ['cost_id'])
                );
            });
        });
        $trip->requirements->each(function($requirement) use ($duplicate) {
            $duplicate->requirements()->create(
                array_except($requirement->toArray(), ['requester_id', 'requester_type'])
            );
        });

        return $this->response->item($duplicate, new TripTransformer);
    }
}
