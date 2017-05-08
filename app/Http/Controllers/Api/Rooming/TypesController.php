<?php

namespace App\Http\Controllers\Api\Rooming;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\Rooming\Interfaces\Type;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\RoomTypeRequest;
use App\Http\Transformers\v1\RoomTypeTransformer;

class TypesController extends Controller
{   
    /**
     * The room type instance
     * 
     * @var App\Repositories\Rooming\Type
     */
    private $type;

    /**
     * RoomTypesControler constructor.
     * 
     * @param App\Repositories\Rooming\Type $type
     */
    function __construct(Type $type)
    {
        $this->type = $type;
    }

    /**
     * Get a paginated list of all room types.
     * 
     * @param  Illuminate\Http\Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $types = $this->type
                      ->filter($request->all())
                      ->paginate($request->get('per_page', 10));

        return $this->response->paginator($types, new RoomTypeTransformer);
    }

    /**
     * Show a specific room type.
     * 
     * @param  String $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $type = $this->type->getById($id);

        return $this->response->item($type, new RoomTypeTransformer);
    }

    /**
     * Create and store a new room type in storage.
     * 
     * @param  App\Http\Requests\v1\RoomTypeRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(RoomTypeRequest $request)
    {
        $type = $this->type->create([
            'name' => $request->get('name'),
            'rules' => $request->get('rules')
        ]);

        return $this->response->item($type, new RoomTypeTransformer);
    }

    /**
     * Update a room type in storage.
     * 
     * @param  App\Http\Requests\v1\RoomTypeRequest $request
     * @param  String          $id      
     * @return \Dingo\Api\Http\Response
     */
    public function update(RoomTypeRequest $request, $id)
    {
        $type = $this->type->update([
            'name' => $request->get('name'),
            'rules' => $request->get('rules')
        ], $id);

        return $this->response->item($type, new RoomTypeTransformer);
    }

    /**
     * Delete a room type from storage.
     * 
     * @param  String $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $type = $this->type->delete($id);

        return $this->response->noContent();
    }
}
