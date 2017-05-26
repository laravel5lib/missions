<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\v1\Region;
use Illuminate\Http\Request;
use App\Models\v1\Accommodation;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\AccommodationRequest;
use App\Http\Transformers\v1\AccommodationTransformer;

class AccommodationsController extends Controller
{

    /**
     * @var Accommodation
     */
    private $accommodation;

    /**
     * AccommodationsController constructor.
     * @param Accommodation $accommodation
     */
    public function __construct(Accommodation $accommodation)
    {
        $this->accommodation = $accommodation;
    }

    /**
     * Get a list of accommodations
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index($regionId, Request $request)
    {
        $accommodation = $this->accommodation
                              ->whereRegionId($regionId)
                              ->filter($request->all())
                              ->paginate($request->get('per_page', 10));

        return $this->response->paginator($accommodation, new AccommodationTransformer);
    }

    /**
     * Create a new accommodation
     *
     * @param AccommodationRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store($regionId, Region $region, AccommodationRequest $request)
    {
        $region = $region->findOrFail($regionId);

        if (! $request->has('country_code')) 
            $request->merge(['country_code' => $region->country_code]);

        $accommodation = $region->accommodations()->create(
            $request->only('name', 'address_one', 'address_two', 'city','state', 
                'zip', 'phone', 'fax', 'country_code', 'email', 'url', 'region_id', 'short_desc'));

        return $this->response->item($accommodation, new AccommodationTransformer);
    }

    /**
     * Get the specified accommodation
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($regionId, $id)
    {
        $accommodation = $this->accommodation->whereRegionId($regionId)->findOrFail($id);

        return $this->response->item($accommodation, new AccommodationTransformer);
    }

    /**
     * Update the specified accommodation
     *
     * @param $id
     * @param AccommodationRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update($regionId, $id, AccommodationRequest $request)
    {
        $accommodation = $this->accommodation->whereRegionId($regionId)->findOrFail($id);

        $accommodation->update([
            'name' => $request->get('name', $accommodation->name),
            'address_one' => $request->get('address_one', $accommodation->address_one),
            'address_two' => $request->get('address_two', $accommodation->address_two),
            'city' => $request->get('city', $accommodation->city),
            'state' => $request->get('state', $accommodation->state),
            'zip' => $request->get('zip', $accommodation->zip),
            'phone' => $request->get('phone', $accommodation->phone),
            'fax' => $request->get('fax', $accommodation->fax),
            'country_code' => $request->get('country_code', $accommodation->country_code),
            'email' => $request->get('email', $accommodation->email),
            'url' => $request->get('url', $accommodation->url),
            'region_id' => $request->get('region_id', $accommodation->region_id),
            'short_desc' => $request->get('short_desc', $accommodation->short_desc)
        ]);

        return $this->response->item($accommodation, new AccommodationTransformer);
    }

    /**
     * Delete the specified accommodation
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($regionId, $id)
    {
        $accommodation = $this->accommodation->whereRegionId($regionId)->findOrFail($id);

        $accommodation->delete();

        return $this->response->noContent();
    }
}
