<?php

namespace App\Http\Controllers\Web;

use App\FundraiserFactory;
use App\Models\v1\Fundraiser;
use App\Models\v1\Fund;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;
use GrahamCampbell\Markdown\Facades\Markdown;

class FundraisersController extends Controller
{
    use SEOTools;

    /**
     * Get all fundraisers.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $fundraisers = $this->api->get('fundraisers');

        $this->seo()->setTitle('Fundraisers');

        return view('site.fundraisers.index', compact('fundraisers'));
    }

    /**
     * Get the fundraiser page by it's url and it's sponsor's url.
     *
     * @param $sponsor_slug
     * @param $fundraiser_slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        if (is_numeric($id)) {
            $fundraiser = Fundraiser::findOrFail($id);
        } else {
            $fundraiser = Fundraiser::whereUuid($id)->first();
        }
        
        if ( ! $fundraiser->public) {
            $this->authorize('view', $fundraiser);
        }

        $this->seo()->setTitle($fundraiser->name)
            ->opengraph()
            ->addImage($fundraiser->featured_image)
            ->setType('article');
        $this->seo()->twitter()->addImage($fundraiser->featured_image);

        return view('site.fundraisers.show', $this->setViewData($fundraiser));
    }

    private function setViewData($fundraiser)
    {   
        return [
            'fundraiser' => $fundraiser,
            'location' => $fundraiser->location,
            'title' => $fundraiser->name,
            'type' => $fundraiser->type,
            'short_description' => $fundraiser->short_desc,
            'featured_image' => $fundraiser->featured_image ?: '/images/placeholders/fundraiser-placeholder.jpg',
            'sponsor_avatar_url' => image($fundraiser->sponsor->getAvatar()->source.'?w=50&h=50'),
            'sponsor_name' => $fundraiser->sponsor->name,
            'dollars_raised' => $fundraiser->dollars_raised,
            'dollars_goal' => $fundraiser->dollars_goal,
            'donor_count' => count($fundraiser->donors),
            'percent_raised' => $fundraiser->getPercentRaised(),
            'deadline' => $fundraiser->ended_at->toDateTimeString(),
            'update_count' => $fundraiser->stories()->count(),
            'content' => $fundraiser->description
        ];
    }

    public function create(Fund $fund)
    {
        $fundraiser = (new FundraiserFactory($fund))->build();

        return view('site.fundraisers.create')->with([
            'fundraiser' => json_encode($fundraiser),
            'cancelUrl' => '/'.request()->segment(1).'/'.request()->route('fund')->fundable_type.'/'.request()->route('fund')->fundable_id.'/funding'
        ]);
    }

    public function edit(Fundraiser $fundraiser)
    {
        $this->authorize('update', $fundraiser);

        $this->seo()->setTitle('Edit Fundraiser');

        return view('site.fundraisers.edit')
            ->with('fundraiser', $fundraiser);
    }
}
