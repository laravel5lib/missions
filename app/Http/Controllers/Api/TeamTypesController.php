<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\v1\TeamType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\TeamTypeRequest;
use App\Http\Transformers\v1\TeamTypeTransformer;

class TeamTypesController extends Controller
{
    private $type;

    function __construct(TeamType $type)
    {
        $this->type = $type;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = $this->type->all();

        return $this->response->collection($types, new TeamTypeTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeamTypeRequest $request)
    {
        $rules = $request->except('name');

        $type = $this->type->create([
            'name' => $request->get('name'),
            'rules' => $rules
        ]);

        return $this->response->item($type, new TeamTypeTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $type = $this->type->findOrFail($id);

        return $this->response->item($type, new TeamTypeTransformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TeamTypeRequest $request, $id)
    {
        $type = $this->type->findOrFail($id);

        $rules = collect($type->rules)->merge($request->except('name'));

        $type->update([
            'name' => $request->get('name', $type->name),
            'rules' => $rules
        ]);

        return $this->response->item($type, new TeamTypeTransformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $type = $this->type->findOrFail($id);

        $type->delete();

        return $this->response->noContent();
    }
}
