<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\v1\Slug;
use App\Http\Controllers\Controller;

class PagesController extends Controller
{
    function __construct(Slug $slug)
    {
        $this->slug = $slug;
    }

    public function show($slug)
    {
        return $this->redirect_to_controller($slug);
    }

    private function redirect_to_controller($slug)
    {
        $resource = $this->lookup_resource_by_slug($slug);

        if ( ! $resource)
          return $this->load_page($slug);

        return $this->find_controller($resource);
    }

    private function lookup_resource_by_slug($slug)
    {
        $resource = $this->slug->where('url', $slug)->first();

        return $resource ? $resource : null;
    }

    private function load_page($slug)
    {
        return view()->exists('site/' . $slug) ? view('site/' . $slug) : abort(404);
    }

    private function find_controller($resource)
    {
        $namespace = 'App\Http\Controllers\Web';

        $controllers = collect([
            'campaigns' => '\CampaignsController',
            'groups' => '\GroupsController',
            'users' => '\UsersController',
            'causes' => '\CausesController'
        ]);

        return app($namespace.$controllers->get($resource->slugable_type))
                   ->show($resource->slugable_id);
    }
}
