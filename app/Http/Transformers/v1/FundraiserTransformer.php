<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Fundraiser;
use App\Models\v1\Group;
use App\Models\v1\User;
use League\Fractal\TransformerAbstract;

class FundraiserTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = ['sponsor'];

    /**
     * Turn this item object into a generic array
     *
     * @param Fundraiser $fundraiser
     * @return array
     */
    public function transform(Fundraiser $fundraiser)
    {
        $fundraiser->load('banner');

        $array = [
            'id'             => $fundraiser->id,
            'name'           => $fundraiser->name,
            'type'           => $fundraiser->type,
            'goal_amount'    => (int) $fundraiser->goal_amount,
            'raised_amount'  => (int) $fundraiser->raised(),
            'raised_percent' => (int) $fundraiser->getPercentRaised(),
            'donors_count'   => (int) count($fundraiser->donors),
            'banner'         => $fundraiser->banner ? image($fundraiser->banner->source) : null,
            'url'            => $fundraiser->url,
            'description'    => $fundraiser->description,
            'started_at'     => $fundraiser->started_at->toDateTimeString(),
            'ended_at'       => $fundraiser->ended_at->toDateTimeString(),
            'created_at'     => $fundraiser->created_at->toDateTimeString(),
            'updated_at'     => $fundraiser->updated_at->toDateTimeString(),
            'links'          => [
                [
                    'rel' => 'self',
                    'uri' => '/fundraisers/' . $fundraiser->id,
                ]
            ]
        ];

        return $array;
    }

    public function includeSponsor(Fundraiser $fundraiser)
    {
        $sponsor = $fundraiser->sponsor;

        if(is_a($sponsor, User::class))
            return $this->item($sponsor, new UserTransformer);

        if(is_a($sponsor, Group::class))
            return $this->item($sponsor, new GroupTransformer);

        return null;
    }

}