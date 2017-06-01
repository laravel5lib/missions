<?php

namespace App\Repositories\Rooming;

use App\Models\v1\RoomType;
use Illuminate\Http\Request;
use App\Repositories\EloquentRepository;
use App\Http\Requests\v1\RoomTypeRequest;
use App\Repositories\Rooming\Interfaces\Type;

class EloquentType extends EloquentRepository implements Type
{

    /**
     * Instance of RoomType Model.
     * 
     * @var App\Models\v1\RoomType
     */
    protected $model;

    protected $attributes = ['name', 'rules'];

    /**
     * Rooming Type Constructor
     * 
     * @param App\Models\v1\RoomType $model
     */
    function __construct(RoomType $model)
    {
        $this->model = $model;
    }

    /**
     * Create a new room type and save in storage.
     * 
     * @param  App\Http\Requests\v1\RoomTypeRequest $request
     * @return App\Repositories\Rooming\Type
     */
    public function create(array $data)
    {
        $rules = $this->model->rules();

        $rules->set('occupancy_limit', isset($data['rules']['occupancy_limit']) ? $data['rules']['occupancy_limit']: 4);
        $rules->set('same_gender', isset($data['rules']['same_gender']) ? $data['rules']['same_gender'] : false);
        $rules->set('married_only', isset($data['rules']['married_only']) ? $data['rules']['married_only']: false);

        return $this->model = $this->model->create([
            'name' => trim($data['name']),
            'rules' => $rules->all()
        ]);
    }

    /**
     * Update an existing room type and save in storage.
     * 
     * @param  App\Http\Requests\v1\RoomTypeRequest $request
     * @return App\Repositories\Rooming\Type
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        $model = $this->getById($id);

        $rules = isset($data['rules']) ? $data['rules'] : $model->rules()->all();

        $model->update([
            'name' => isset($data['name']) ? $data['name'] : $model->name,
            'rules' => $model->rules()->merge($rules)->all()
        ]);

        return $model;
    }

    /**
     * Get room types that belong to a plan
     */
    public function belongsToPlan($planId)
    {
        $this->filter(['plan' => $planId]);

        return $this;
    }
}
