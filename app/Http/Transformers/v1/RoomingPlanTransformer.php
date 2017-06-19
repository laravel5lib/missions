<?php
namespace App\Http\Transformers\v1;

use App\RoomCount;
use App\Models\v1\RoomingPlan;
use League\Fractal\TransformerAbstract;

class RoomingPlanTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = ['rooms', 'group'];

    public function transform(RoomingPlan $plan)
    {
        return [
            'id'          => $plan->id,
            'name'        => $plan->name,
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

    public function includeRooms(RoomingPlan $plan)
    {
        $rooms = $plan->rooms;

        return $this->collection($rooms, new RoomTransformer);
    }

    public function includeGroup(RoomingPlan $plan)
    {
        $group = $plan->group;

        return $this->item($group, new GroupTransformer);
    }

}