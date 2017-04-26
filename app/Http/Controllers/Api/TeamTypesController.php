<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
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
        $type = $this->type->create([
            'name' => $request->get('name');
            'rules' => [
                'min_members' => $request->get('min_members', 0),
                'max_members' => $request->get('max_members', 0),
                'min_leaders' => $request->get('min_leaders', 0),
                'max_leaders' => $request->get('max_leaders', 0),
                'min_squads'  => $request->get('min_squads', 0),
                'max_squads'  => $request->get('max_squads', 0),
                'min_squad_members' => $request->get('min_squad_members', 0),
                'max_squad_members' => $request->get('max_squad_members', 0),
                'min_squad_leaders' => $request->get('min_squad_leaders', 0),
                'max_squad_leaders' => $request->get('max_squad_leaders', 0)
            ]
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
