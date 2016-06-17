<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\RegionRequest;
use App\Http\Transformers\v1\RegionTransformer;
use App\Models\v1\Region;
use Illuminate\Http\Request;
use App\Http\Requests;

class RegionsController extends Controller
{

    /**
     * @var Region
     */
    private $region;

    /**
     * RegionsController constructor.
     * @param Region $region
     */
    public function __construct(Region $region)
    {
        $this->region = $region;

        $this->middleware('api.auth');
        $this->middleware('jwt.refresh');
    }

    /**
     * Return a paginated list of regions
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        if ( ! $this->auth->user()->isAdmin()) {
            abort(403);
        }

        $regions = $this->region->filter($request->all())
            ->paginate($request->get('per_page', 10));

        return $this->response->paginator($regions, new RegionTransformer);
    }

    /**
     * Create a new region
     *
     * @param RegionRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(RegionRequest $request)
    {
        $region = $this->region->create($request->all());

        return $this->response->item($region, new RegionTransformer);
    }

    /**
     * Return a specific region by id
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        if ( ! $this->auth->user()->isAdmin()) {
            abort(403);
        }

        $region = $this->region->findOrFail($id);

        return $this->response->item($region, new RegionTransformer);
    }

    /**
     * Update a specific region by id
     *
     * @param RegionRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(RegionRequest $request, $id)
    {
        $region = $this->region->findOrFail($id);

        $region->update($request->all());

        return $this->response->item($region, new RegionTransformer);
    }

    /**
     * Delete a specific region by id
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        if ( ! $this->auth->user()->isAdmin()) {
            abort(403);
        }
        
        $region = $this->region->findOrFail($id);

        $region->delete();

        return $this->response->noContent();
    }
}
