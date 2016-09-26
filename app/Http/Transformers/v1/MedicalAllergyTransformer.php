<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\MedicalAllergy;
use League\Fractal\TransformerAbstract;

class MedicalAllergyTransformer extends TransformerAbstract
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
     * @param MedicalAllergy $allergy
     * @return array
     */
    public function transform(MedicalAllergy $allergy)
    {
        return [
            'id'         => $allergy->id,
            'name'       => $allergy->name,
            'medication' => (boolean) $allergy->medication,
            'diagnosed'  => (boolean) $allergy->diagnosed,
            'created_at' => $allergy->created_at->toDateTimeString(),
            'updated_at' => $allergy->updated_at->toDateTimeString(),
        ];
    }

    /**
     * Include attachments
     *
     * @param MedicalAllergy $allergy
     * @return \League\Fractal\Resource\Collection
     */
    public function includeAttachments(MedicalAllergy $allergy)
    {
        $attachments = $allergy->attachments;

        return $this->collection($attachments, new UploadTransformer);
    }
}