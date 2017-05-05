<?php

namespace App\Http\Transformers\v1;
use App\Models\v1\Room;
use League\Fractal\TransformerAbstract;

class RoomTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = ['type'];

    /**
     * Transform the object into a basic array
     *
     * @param Room $room
     * @return array
     */
    public function transform(Room $room)
    {
        return [
            'id'              => $room->id,
            'type'            => $room->type->name,
            'label'           => $room->label,
            'occupants_count' => $room->occupants_count,
            'created_at'      => $room->created_at->toDateTimeString(),
            'updated_at'      => $room->updated_at->toDateTimeString(),
            'deleted_at'      => $room->deleted_at ? $room->deleted_at->toDateTimeString() : null,
            'links'           => [
                [
                    'rel' => 'self',
                    'uri' => '/api/rooming/rooms/' . $room->id,
                ]
            ]
        ];
    }

    public function includeType(Room $room)
    {
        $type = $room->type;

        return $this->item($type, new RoomTypeTransformer);
    }
}