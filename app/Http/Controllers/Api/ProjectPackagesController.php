<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\v1\ProjectPackageRequest;
use App\Http\Transformers\v1\ProjectPackageTransformer;
use App\Models\v1\ProjectInitiative;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectPackagesController extends Controller
{

    /**
     * @var ProjectInitiative
     */
    private $initiative;

    /**
     * ProjectPackagesController constructor.
     * @param ProjectInitiative $initiative
     */
    public function __construct(ProjectInitiative $initiative)
    {
        $this->initiative = $initiative;
    }

    /**
     * @param $id
     * @return mixed
     */
    protected function initiative($id)
    {
        return $this->initiative->findOrFail($id);
    }

    /**
     * Get a list of project packages.
     *
     * @param $id
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index($id, Request $request)
    {
        $packages = $this->initiative($id)
                         ->packages()
                         ->paginate($request->get('per_page'));

        return $this->response->paginator($packages, new ProjectPackageTransformer);
    }

    /**
     * Get a single package by it's id.
     *
     * @param $initiative_id
     * @param $package_id
     * @return \Dingo\Api\Http\Response
     */
    public function show($initiative_id, $package_id)
    {
        $package = $this->initiative($initiative_id)
                        ->packages()
                        ->findOrFail($package_id);

        return $this->response->item($package, new ProjectPackageTransformer);
    }

    /**
     * Create a new project package.
     *
     * @param $id
     * @param ProjectPackageRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store($id, ProjectPackageRequest $request)
    {
        $package = $this->initiative($id)
                        ->packages()
                        ->create($request->all());

        return $this->response->item($package, new ProjectPackageTransformer);
    }

    /**
     * Update a package by it's id.
     *
     * @param $initiative_id
     * @param $package_id
     * @param ProjectPackageRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update($initiative_id, $package_id, ProjectPackageRequest $request)
    {
        $package = $this->initiative($initiative_id)
                        ->packages()
                        ->findOrFail($package_id);

        $package->update($request->all());

        return $this->response->item($package, new ProjectPackageTransformer);
    }

    /**
     * Delete package(s).
     *
     * @param $initiative_id
     * @param null $package_id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($initiative_id, $package_id = null)
    {
        $packages = $this->initiative($initiative_id)->packages();

        if ( ! is_null($package_id)) $packages->findOrFail($package_id);

        $packages->delete();

        return $this->response->noContent();
    }
}
