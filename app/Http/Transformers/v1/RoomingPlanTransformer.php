<?php
namespace App\Http\Transformers\v1;

use App\Models\v1\RoomingPlan;
use League\Fractal\TransformerAbstract;

class RoomingPlanTransformer extends TransformerAbstract
{
    public function transform(RoomingPlan $plan)
    {
        return [
            'id'         => $plan->id,
            'name'       => $plan->name,
            'short_desc' => $plan->short_desc,
            'created_at' => $plan->created_at->toDateTimeString(),
            'updated_at' => $plan->updated_at->toDateTimeString()
        ];
    }
}