<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\TripPromoHandler;
use App\GroupPromoHandler;
use Illuminate\Http\Request;
use App\CampaignPromoHandler;
use App\Models\v1\Promotional;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\PromotionalRequest;
use App\Http\Transformers\v1\PromotionalTransformer;

class PromotionalsController extends Controller
{
    protected $promotional;

    public function __construct(Promotional $promotional)
    {
        $this->promotional = $promotional;
    }

    public function index(Request $request)
    {
        $promos = $this->promotional
             ->filter($request->all())
             ->withCount('promocodes')
             ->paginate($request->get('per_page', 10));

        return $this->response->paginator($promos, new PromotionalTransformer);
    }

    public function show($id)
    {
        $promo = $this->promotional->withTrashed()->withCount('promocodes')->findOrFail($id);

        return $this->response->item($promo, new PromotionalTransformer);
    }

    public function store(PromotionalRequest $request)
    {
       $promo = $this->getPromoHandler($request)->create($request);

       return $this->response->item($promo, new PromotionalTransformer);
    }

    public function update($id, PromotionalRequest $request)
    {
        $promo = $this->promotional->findOrFail($id);

        $promo->update([
            'name' => $request->get('name', $promo->name),
            'reward' => $request->get('reward', $promo->reward),
            'expires_at' => $request->get('expires_at', $promo->expires_at)
        ]);

        return $this->response->item($promo, new PromotionalTransformer);
    }

    public function destroy($id, Request $request)
    {
        if ($request->get('force')) {
            $promo = $this->promotional->withTrashed()->findOrFail($id);
            $promo->forceDelete();

            return $this->response->noContent();
        }

        $promo = $this->promotional->findOrFail($id);

        $promo->promocodes()->delete();
        $promo->delete();

        return $this->response->noContent();
    }

    public function restore($id)
    {
        $promo = $this->promotional->withTrashed()->findOrFail($id);

        $promo->restore();
        $promo->promocodes()->restore();

        return $this->response->item($promo, new PromotionalTransformer);
    }

    /**
     * Get the promotional type handler
     * 
     * @return mixed
     */
    private function getPromoHandler($request)
    {
        switch ($request->get('promoteable_type')) {
            case 'campaigns':
                return app()->make(CampaignPromoHandler::class);
            case 'trips':
                return app()->make(TripPromoHandler::class);
            case 'groups':
                return app()->make(GroupPromoHandler::class);
            default:
                return app()->make(CampaignPromoHandler::class);
        }
    }
}
