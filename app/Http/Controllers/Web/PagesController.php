<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests;
use App\Models\v1\Slug;
use Illuminate\Http\Request;
use App\Models\v1\Fundraiser;
use App\Http\Controllers\Controller;

class PagesController extends Controller
{
    private $slug;
    private $fundraiser;

    function __construct(Slug $slug, Fundraiser $fundraiser)
    {
        $this->slug = $slug;
        $this->fundraiser = $fundraiser;
    }

    public function show($slug)
    {
        $fundraiser = $this->fundraiser->where('url', $slug)->first();

        if ($fundraiser) {
            return redirect($fundraiser->sponsor->slug->url.'/'.$slug);
        }

        return $this->redirect_to_controller($slug);
    }

    private function redirect_to_controller($slug)
    {
        $resource = $this->lookup_resource_by_slug($slug);

        if (! $resource) {
            return $this->load_page($slug);
        }

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
            'users' => '\UsersController'
        ]);

        return app($namespace.$controllers->get($resource->slugable_type))
                   ->show($resource->slugable_id);
    }
}
