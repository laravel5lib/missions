<?php

namespace App\Http\Controllers\Api\Interaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Interaction\SiteRequest;
use App\Http\Transformers\v1\Interaction\SiteTransformer;
use App\Models\v1\Interaction\Site;
use Illuminate\Http\Request;

use App\Http\Requests;

class SitesController extends Controller
{

    /**
     * @var Site
     */
    private $site;

    /**
     * ContactsController constructor.
     * @param Site $site
     */
    public function __construct(Site $site)
    {
        $this->site = $site;

        $this->middleware('api.auth');
//        $this->middleware('jwt.refresh');
    }

    public function index(Request $request)
    {
        $sites = $this->site
                      ->filter($request->all())
                      ->paginate($request->get('per_page', 10));

        return $this->response->paginator($sites, new SiteTransformer);
    }

    public function store(SiteRequest $request)
    {
        $site = $this->site->create($request->all());

        $location = url('/interactions/sites' . $site->id);

        return $this->response->created($location);
    }

    public function show($id)
    {
        $site = $this->site->findOrFail($id);

        return $this->response->item($site, new SiteTransformer);
    }

    public function update(SiteRequest $request, $id)
    {
        $site = $this->site->findOrFail($id);

        $site->update($request->all());

        return $this->response->item($site, new SiteTransformer);
    }

    public function destroy($id)
    {
        $site = $this->site->findOrFail($id);

        $site->delete();

        return $this->response->noContent();
    }
}
