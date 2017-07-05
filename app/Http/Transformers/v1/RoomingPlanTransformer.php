<?php
namespace App\Http\Transformers\v1;

use App\RoomCount;
use League\Fractal\ParamBag;
use App\Models\v1\RoomingPlan;
use League\Fractal\TransformerAbstract;

class RoomingPlanTransformer extends TransformerAbstract
{
    private $validParams = ['notInUse'];

    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = ['rooms', 'groups'];

    public function transform(RoomingPlan $plan)
    {
        return [
            'id'          => $plan->id,
            'name'        => $plan->name,
            'locked'      => (boolean) $plan->locked,
            'short_desc'  => $plan->short_desc,
            'room_types'  => $this->getAvailableRooms($plan),
            'rooms_count' => $plan->roomsCount()->all(),
            'occupants_count' => $plan->occupantsCount()->total(),
            'created_at'  => $plan->created_at->toDateTimeString(),
            'updated_at'  => $plan->updated_at->toDateTimeString(),
            'deleted_at'  => $plan->deleted_at ? $plan->deleted_at->toDateTimeString() : null,
            'links'       => [
                [
                    'rel' => 'self',
                    'uri' => '/api/rooming/plans/' . $plan->id,
                ]
            ],
        ];
    }

    private function getAvailableRooms(RoomingPlan $plan)
    {
        $available = $plan->availableRoomTypes->keyBy('name')->map(function($type) {
            return $type->pivot->available_rooms;
        });

        $available = $available->put('total', $available->sum())->all();

        return array_merge((new RoomCount($plan))->getRoomTypes(), $available);
    }

    public function includeRooms(RoomingPlan $plan, ParamBag $params = null)
    {
        if (! is_null($params) && $params->get('notInUse')) {
            $this->validateParams($params);

            $rooms = $plan->rooms()->filter(['notInUse' => $params->get('notInUse')])->get();

        } else {
            $rooms = $plan->rooms;
        }

        return $this->collection($rooms, new RoomTransformer);
    }

    public function includeGroups(RoomingPlan $plan)
    {
        $group = $plan->groups;

        return $this->collection($group, new GroupTransformer);
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