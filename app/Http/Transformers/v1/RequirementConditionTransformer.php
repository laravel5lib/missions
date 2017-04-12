<?php

namespace App\Http\Transformers\v1;
use App\Models\v1\RequirementCondition;
use League\Fractal\TransformerAbstract;

class RequirementConditionTransformer extends TransformerAbstract
{
    public function transform(RequirementCondition $condition)
    {
        return [
            'id'             => $condition->id,
            'requirement_id' => $condition->requirement_id,
            'type'           => $condition->type,
            'operator'       => $condition->operator,
            'applies_to'     => $condition->applies_to,
            'created_at'     => $condition->created_at->toDateTimeString(),
            'updated_at'     => $condition->updated_at->toDateTimeString()
        ];
    }
}