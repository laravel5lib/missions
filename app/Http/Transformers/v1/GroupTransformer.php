<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Group;
use League\Fractal\TransformerAbstract;

class GroupTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'trips', 'managers', 'facilitators', 'fundraisers', 'uploads'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Group $group
     * @return array
     */
    public function transform(Group $group)
    {
        $group->load('avatar', 'banner');

        return [
            'id'           => $group->id,
            'name'         => $group->name,
            'type'         => $group->type,
            'timezone'     => $group->timezone,
            'description'  => $group->description,
            'url'          => $group->url,
            'public'       => (bool) $group->public,
            'address_one'  => $group->address_one,
            'address_two'  => $group->address_two,
            'city'         => $group->city,
            'state'        => $group->state,
            'zip'          => $group->zip,
            'country_code' => $group->country_code,
            'country_name' => country($group->country_code),
            'phone_one'    => $group->phone_one,
            'phone_two'    => $group->phone_two,
            'email'        => $group->email,
            'avatar'       => $group->avatar ? image($group->avatar->source) : null,
            'banner'       => $group->banner ? image($group->banner->source) : null,
            'created_at'   => $group->created_at->toDateTimeString(),
            'updated_at'   => $group->updated_at->toDateTimeString(),
            'links'        => [
                [
                    'rel' => 'self',
                    'uri' => '/groups/' . $group->id,
                ]
            ],
        ];
    }

    /**
     * Include Trips
     *
     * @param Group $group
     * @return \League\Fractal\Resource\Item
     */
    public function includeTrips(Group $group)
    {
        $trips = $group->trips;

        return $this->collection($trips, new TripTransformer);
    }

    /**
     * Include Managers
     *
     * @param Group $group
     * @return \League\Fractal\Resource\Item
     */
    public function includeManagers(Group $group)
    {
        $managers = $group->managers;

        return $this->collection($managers, new UserTransformer);
    }

    /**
     * Include Facilitators
     *
     * @param Group $group
     * @return \League\Fractal\Resource\Item
     */
    public function includeFacilitators(Group $group)
    {
        $facilitators = $group->facilitators;

        return $this->collection($facilitators, new FacilitatorTransformer);
    }

    /**
     * Include Fundraisers
     *
     * @param Group $group
     * @return \League\Fractal\Resource\Collection
     */
    public function includeFundraisers(Group $group)
    {
        $fundraisers = $group->fundraisers;

        return $this->collection($fundraisers, new FundraiserTransformer);
    }

    /**
     * Include Uploads
     *
     * @param Group $group
     * @return \League\Fractal\Resource\Collection
     */
    public function includeUploads(Group $group)
    {
        $uploads = $group->uploads;

        return $this->collection($uploads, new UploadTransformer);
    }

}