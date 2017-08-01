<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\MedicalCondition;
use League\Fractal\TransformerAbstract;

class MedicalConditionTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = ['attachments'];

    /**
     * Turn this item object into a generic array
     *
     * @param MedicalCondition $condition
     * @return array
     */
    public function transform(MedicalCondition $condition)
    {
        return [
            'id'         => $condition->id,
            'name'       => $condition->name,
            'medication' => (boolean) $condition->medication,
            'diagnosed'  => (boolean) $condition->diagnosed,
            'created_at' => $condition->created_at->toDateTimeString(),
            'updated_at' => $condition->updated_at->toDateTimeString(),
        ];
    }

    /**
     * Include attachments
     *
     * @param MedicalCondition $condition
     * @return \League\Fractal\Resource\Collection
     */
    public function includeAttachments(MedicalCondition $condition)
    {
        $attachments = $condition->attachments;

        return $this->collection($attachments, new UploadTransformer);
    }
}
