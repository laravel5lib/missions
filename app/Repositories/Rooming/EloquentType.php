<?php

namespace App\Repositories\Rooming;

use App\Models\v1\RoomType;
use Illuminate\Http\Request;
use App\Http\Requests\v1\RoomTypeRequest;
use App\Repositories\Rooming\Interfaces\Type;

class EloquentType implements Type
{

    /**
     * Instance of RoomType Model.
     * 
     * @var App\Models\v1\RoomType
     */
    protected $type;

    /**
     * Rooming Type Constructor
     * 
     * @param App\Models\v1\RoomType $type
     */
    function __construct(RoomType $type)
    {
        $this->type = $type;
    }

    /**
     * Filter room types by request.
     * 
     * @param  Illuminate\Http\Request $request
     * @return App\Repositories\Rooming\Type
     */
    public function filter(Request $request)
    {
        $this->type->filter($request->all());

        return $this;
    }

    /**
     * Find a room type by it's id.
     * 
     * @param  String $id
     * @return App\Models\v1\RoomType
     */
    public function find($id)
    {
        $this->type = $this->type->withTrashed()->findOrFail($id);

        return $this;
    }

    /**
     * Make a new room type and save in storage.
     * 
     * @param  App\Http\Requests\v1\RoomTypeRequest $request
     * @return App\Repositories\Rooming\Type
     */
    public function make(RoomTypeRequest $request)
    {
        $rules = $this->type->rules();

        $rules->set('occupancy_limit', $request->json('rules.occupancy_limit', 4));
        $rules->set('same_gender', $request->json('rules.same_gender', false));
        $rules->set('married_only', $request->json('rules.married_only', false));

        $this->type = $this->type->create([
            'name' => $request->get('name'),
            'rules' => $rules->all()
        ]);

        return $this;
    }

    /**
     * Modify an existing room type and update in storage.
     * 
     * @param  App\Http\Requests\v1\RoomTypeRequest $request
     * @return App\Repositories\Rooming\Type
     */
    public function modify(RoomTypeRequest $request)
    {
        $rules = $request->get('rules', $this->type->rules);

        $this->type->update([
            'name' => $request->get('name', $this->type->name),
            'rules' => $this->type->rules()->merge($rules)->all()
        ]);

        return $this;
    }

    /**
     * Get the room type.
     * 
     * @return App\Models\v1\RoomType
     */
    public function get()
    {
        return $this->type;
    }

    /**
     * Get all room types.
     * 
     * @return App\Models\v1\RoomType
     */
    public function all()
    {
        return $this->type->all();
    }

    /**
     * Get a paginated list of room types.
     * 
     * @param  integer $perPage
     * @return App\Models\v1\RoomType
     */
    public function paginate($perPage = 10)
    {
        return $this->type->paginate($perPage);
    }

    /**
     * Delete the room type.
     * 
     * @return boolean
     */
    public function delete()
    {
        $this->type->delete();

        return true;
    }
}
