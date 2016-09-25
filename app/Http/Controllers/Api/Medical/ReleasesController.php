<?php

namespace App\Http\Controllers\Api\Medical;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Medical\ReleaseRequest;
use App\Http\Transformers\v1\ReleaseTransformer;
use App\Models\v1\MedicalRelease;

use App\Http\Requests;
use Dingo\Api\Contract\Http\Request;

class ReleasesController extends Controller
{

    /**
     * @var MedicalRelease
     */
    private $release;

    /**
     * ReleasesController constructor.
     * @param MedicalRelease $release
     */
    public function __construct(MedicalRelease $release)
    {
        $this->release = $release;

        $this->middleware('api.auth',  ['only' => ['store','update','destroy']]);
//        $this->middleware('jwt.refresh');
    }

    /**
     * Get all medical releases.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $releases = $this->release;

        if($request->has('user_id'))
            $releases = $releases->where('user_id', $request->get('user_id'));

        $releases = $releases->paginate($request->get('per_page', 25));

        return $this->response->paginator($releases, new ReleaseTransformer);
    }

    /**
     * Get the specified medical release.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $release = $this->release->findOrFail($id);

        return $this->response->item($release, new ReleaseTransformer);
    }

    /**
     * Create a new medical release and save in storage.
     *
     * @param ReleaseRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(ReleaseRequest $request)
    {
        if ( ! empty($request->get('conditons')))
            $request->merge(['is_risk' => true]);

        $release = $this->release->create($request->all());

        return $this->response->item($release, new ReleaseTransformer);
    }

    /**
     * Update the specified medical release in storage.
     *
     * @param ReleaseRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(ReleaseRequest $request, $id)
    {
        $release = $this->release->findOrFail($id);

        $release->update($request->all());

        return $this->response->item($release, new ReleaseTransformer);
    }

    /**
     * Remove the specified medical release from storage.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $release = $this->release->findOrFail($id);

        $release->delete();

        return $this->response->noContent();
    }
}
