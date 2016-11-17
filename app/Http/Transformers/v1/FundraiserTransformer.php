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
    protected $availableIncludes = ['sponsor', 'fund', 'uploads'];

    /**
     * Turn this item object into a generic array
     *
     * @param Fundraiser $fundraiser
     * @return array
     */
    public function transform(Fundraiser $fundraiser)
    {
        $array = [
            'id'             => $fundraiser->id,
            'name'           => $fundraiser->name,
            'type'           => $fundraiser->type,
            'fund_id'        => $fundraiser->fund_id,
            'goal_amount'    => (int) $fundraiser->goal_amount,
            'raised_amount'  => (int) $fundraiser->raised(),
            'raised_percent' => (int) $fundraiser->getPercentRaised(),
            'donors_count'   => (int) count($fundraiser->donors),
            'sponsor_type'   => $fundraiser->sponsor_type,
            'url'            => $fundraiser->url,
            'public'         => (bool) $fundraiser->public,
            'show_donors'    => (bool) $fundraiser->show_donors,
            'description'    => $fundraiser->description,
            'started_at'     => $fundraiser->started_at->toDateTimeString(),
            'ended_at'       => $fundraiser->ended_at->toDateTimeString(),
            'created_at'     => $fundraiser->created_at->toDateTimeString(),
            'updated_at'     => $fundraiser->updated_at->toDateTimeString(),
            'tags'           => $fundraiser->tagSlugs(),
            'links'          => [
                [
                    'rel' => 'self',
                    'uri' => url('/api/fundraisers/' . $fundraiser->id),
                ],
                [
                    'rel' => 'donors',
                    'uri' => url('/api/fundraisers/' . $fundraiser->id . '/donors'),
                ],
                [
                    'rel' => 'donations',
                    'uri' => url('/api/fundraisers/' . $fundraiser->id . '/donations'),
                ]
            ]
        ];

        return $array;
    }

    /**
     * Include the fundraiser's sponsor.
     *
     * @param Fundraiser $fundraiser
     * @return \League\Fractal\Resource\Item|null
     */
    public function includeSponsor(Fundraiser $fundraiser)
    {
        $sponsor = $fundraiser->sponsor;

        if(is_a($sponsor, User::class))
            return $this->item($sponsor, new UserTransformer);

        if(is_a($sponsor, Group::class))
            return $this->item($sponsor, new GroupTransformer);

        return null;
    }

    /**
     * Include the fund associated with the fundraiser.
     *
     * @param Fundraiser $fundraiser
     * @return \League\Fractal\Resource\Item
     */
    public function includeFund(Fundraiser $fundraiser)
    {
        $fund = $fundraiser->fund;

        return $this->item($fund, new FundTransformer);
    }

    /**
     * Include the uploads associated with the fundraiser.
     *
     * @param Fundraiser $fundraiser
     * @return \League\Fractal\Resource\Collection
     */
    public function includeUploads(Fundraiser $fundraiser)
    {
        $uploads = $fundraiser->uploads;

        return $this->collection($uploads, new UploadTransformer);
    }

}