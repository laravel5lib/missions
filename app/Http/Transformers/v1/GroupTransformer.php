<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Group;
use League\Fractal\ParamBag;
use League\Fractal\TransformerAbstract;

class GroupTransformer extends TransformerAbstract
{
    private $validParams = ['public', 'private', 'status'];

    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'trips', 'managers', 'facilitators', 'fundraisers',
        'uploads', 'social', 'notes'
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
            'status'       => $group->status,
            'name'         => $group->name,
            'type'         => $group->type,
            'timezone'     => $group->timezone,
            'description'  => $group->description,
            'url'          => $group->slug ? $group->slug->url : null,
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
            'avatar'       => $group->avatar ? image($group->avatar->source) : url('/images/placeholders/logo-placeholder.png'),
            'banner'       => $group->banner ? image($group->banner->source) : null,
            'reservations_count' => $group->activeReservations()->count(),
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

    public function includeSocial(Group $group)
    {
        $links = $group->social;

        return $this->collection($links, new LinkTransformer);
    }

    /**
     * Include Trips
     *
     * @param Group $group
     * @param ParamBag $params
     * @return mixed
     */
    public function includeTrips(Group $group, ParamBag $params = null)
    {
        if ( ! is_null($params)) {
            $this->validateParams($params);

            $trips = [];

            if ($params->get('public')) {
                $trips = $group->trips()->filter(['onlyPublic' => true])->get();
            }

            if ($params->get('private')) {
                $trips = $group->trips()->filter(['onlyPrivate' => true])->get();
            }

            if ($params->get('status')) {
                $trips = $group->trips()->filter(['status' => $params->get('status')[0]])->get();
            }

        } else {
            $trips = $group->trips;
        }

        return $this->collection($trips, new TripTransformer);
    }

    /**
     * Include Managers
     *
     * @param Group $group
     * @return \League\Fractal\Resource\Collection
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
     * @return \League\Fractal\Resource\Collection
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

    /**
     * Include most recent notes.
     *
     * @param Group $group
     * @return \League\Fractal\Resource\Collection
     */
    public function includeNotes(Group $group)
    {
        $notes = $group->notes()->recent()->get();

        return $this->collection($notes, new NoteTransformer);
    }

    private function validateParams($params)
    {
        $usedParams = array_keys(iterator_to_array($params));
        if ($invalidParams = array_diff($usedParams, $this->validParams)) {
            throw new \Exception(sprintf(
                'Invalid param(s): "%s". Valid param(s): "%s"',
                implode(',', $usedParams),
                implode(',', $this->validParams)
            ));
        }
    }
}