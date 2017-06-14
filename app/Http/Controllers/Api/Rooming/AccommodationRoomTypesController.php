<?php

namespace App\Http\Controllers\Api\Rooming;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Rooming\Interfaces\Type;
use App\Http\Transformers\v1\RoomTypeTransformer;
use App\Http\Transformers\v1\AccommodationTransformer;
use App\Repositories\Rooming\Interfaces\Accommodation;

class AccommodationRoomTypesController extends Controller
{
    protected $accommodation;
    protected $type;

    function __construct(Accommodation $accommodation, Type $type)
    {
        $this->accommodation = $accommodation;
        $this->type = $type;
    }

    public function index($accommodationId)
    {
        $types = $this->type->belongsToAccommodation($accommodationId)->getAll();

        return $this->response->collection($types, new RoomTypeTransformer);
    }

    public function show($accommodationId, $typeId)
    {
        $type = $this->type->belongsToAccommodation($accommodationId)->getById($typeId);

        return $this->response->item($type, new RoomTypeTransformer);
    }

    public function store($accommodationId, Request $request)
    {
        $validator = $this->validate($request, [
           'room_type_id' => 'required|exists:room_types,id',
           'available_rooms' => 'required|integer'
        ]);

        $data = $request->only('room_type_id', 'available_rooms');

        $accommodation = $this->accommodation->addRoomType($data, $accommodationId);

        return $this->response->item($accommodation, new AccommodationTransformer);
    }

    public function update($accommodationId, $typeId, Request $request)
    {
        $this->validate($request, [
           'available_rooms' => 'required|integer'
        ]);

        $data = $request->only('available_rooms');

        $accommodation = $this->accommodation->updateRoomType($data, $typeId, $accommodationId);

        return $this->response->item($accommodation, new AccommodationTransformer);
    }

    public function destroy($accommodationId, $typeId)
    {
        $this->accommodation->removeRoomType($typeId, $accommodationId);

        return $this->response->noContent();
    }
}
