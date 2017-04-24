<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\v1\RoomType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\RoomTypeRequest;
use App\Http\Transformers\v1\RoomTypeTransformer;

class RoomTypesController extends Controller
{
    private $type;

    function __construct(RoomType $type)
    {
        $this->type = $type;
    }

    public function index(Request $request)
    {
        $types = $this->type
            ->paginate($request->get('per_page', 10));

        return $this->response->paginator($types, new RoomTypeTransformer);
    }

    public function show($id)
    {
        $type = $this->type->findOrFail($id);

        return $this->response->item($type, new RoomTypeTransformer);
    }

    public function store(RoomTypeRequest $request)
    {
        $type = $this->type->create([
            'name' => $request->get('name'),
            'occupancy' => $request->get('occupancy', 1)
        ]);

        return $this->response->item($type, new RoomTypeTransformer);
    }

    public function update(RoomTypeRequest $request, $id)
    {
        $type = $this->type->findOrFail($id);

        $type->update([
            'name' => $request->get('name', $type->name),
            'occupancy' => $request->get('occupancy', $type->occupancy)
        ]);

        return $this->response->item($type, new RoomTypeTransformer);
    }

    public function destroy($id)
    {
        $type = $this->type->findOrFail($id);

        $type->delete();

        return $this->response->noContent();
    }
}
