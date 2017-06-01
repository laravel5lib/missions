<?php

namespace App\Http\Controllers\Api\Rooming;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Rooming\Interfaces\Plan;
use App\Repositories\Rooming\Interfaces\Type;
use App\Http\Transformers\v1\RoomTypeTransformer;

class PlanRoomTypesController extends Controller
{
    protected $plan;
    protected $type;

    function __construct(Plan $plan, Type $type)
    {
        $this->plan = $plan;
        $this->type = $type;
    }

    public function index($planId)
    {
        $types = $this->type->belongsToPlan($planId)->getAll();

        return $this->response->collection($types, new RoomTypeTransformer);
    }

    public function show($planId, $typeId)
    {
        $type = $this->type->belongsToPlan($planId)->getById($typeId);

        return $this->response->item($type, new RoomTypeTransformer);
    }

    public function store($planId, Request $request)
    {
        $validator = $this->validate($request, [
           'room_type_id' => 'required|exists:room_types,id',
           'available_rooms' => 'required|integer'
        ]);

        $data = $request->only('room_type_id', 'available_rooms');

        $types = $this->plan->addRoomType($data, $planId);

        return $this->response->collection($types, new RoomTypeTransformer);
    }

    public function update($planId, $typeId, Request $request)
    {
        $this->validate($request, [
           'available_rooms' => 'required|integer'
        ]);

        $data = $request->only('available_rooms');

        $types = $this->plan->updateRoomType($typeId, $planId);

        return $this->response->collection($types, new RoomTypeTransformer);
    }

    public function destroy($planId, $typeId)
    {
        $this->plan->removeRoomType($typeId, $planId);

        return $this->response->noContent();
    }
}
