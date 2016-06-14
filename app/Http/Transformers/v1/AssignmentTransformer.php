<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Assignment;
use League\Fractal\TransformerAbstract;

class AssignmentTransformer extends TransformerAbstract {

    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'user', 'campaign'
    ];

    /**
     * Transform the object into a basic array
     *
     * @param Assignment $assignment
     * @return array
     */
    public function transform(Assignment $assignment)
    {
        return [
            'id'          => $assignment->id,
            'given_names' => $assignment->given_names,
            'surname'     => $assignment->surname,
            'gender'      => $assignment->gender,
            'status'      => $assignment->status,
            'birthday'    => $assignment->birthday ? $assignment->birthday->toDateTimeString() : null,
            'created_at'  => $assignment->created_at->toDateTimeString(),
            'updated_at'  => $assignment->updated_at->toDateTimeString(),
            'links'       => [
                [
                    'rel' => 'self',
                    'uri' => '/assignments/' . $assignment->id
                ]
            ]
        ];
    }

    /**
     * Include User
     *
     * @param Assignment $assignment
     * @return \League\Fractal\Resource\Item
     */
    public function includeRegion(Assignment $assignment)
    {
        $user = $assignment->user;

        return $this->item($user, new UserTransformer);
    }

    /**
     * Include Campaign
     *
     * @param Assignment $assignment
     * @return \League\Fractal\Resource\Item
     */
    public function includeCampaign(Assignment $assignment)
    {
        $campaign = $assignment->campaign;

        return $this->item($campaign, new CampaignTransformer);
    }
}