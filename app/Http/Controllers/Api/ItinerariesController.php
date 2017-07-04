<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\v1\Itinerary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\ItineraryRequest;
use App\Http\Transformers\v1\ItineraryTransformer;

class ItinerariesController extends Controller
{
    private $itinerary;

    function __construct(Itinerary $itinerary)
    {
        $this->itinerary = $itinerary;
    }

    public function index(Request $request)
    {
        $itinerary = $this->itinerary
            ->withCount('activities')
            ->filter($request->all())
            ->paginate($request->get('per_page', 10));

        return $this->response->paginator($itinerary, new ItineraryTransformer);
    }

    public function show($id)
    {
        $itinerary = $this->itinerary->withCount('activities')->findOrFail($id);

        return $this->response->item($itinerary, new ItineraryTransformer);
    }

    public function store(ItineraryRequest $request)
    {
        $itinerary = $this->itinerary->create([
            'name' => $request->get('name'),
            'curator_id' => $request->get('curator_id'),
            'curator_type' => $request->get('curator_type')
        ]);

        return $this->response->item($itinerary, new ItineraryTransformer);
    }

    public function update($id, ItineraryRequest $request)
    {
        $itinerary = $this->itinerary->findOrFail($id);

        $itinerary->update([
            'name' => $request->get('name', $itinerary->name),
            'curator_id' => $request->get('curator_id', $itinerary->curator_id),
            'curator_type' => $request->get('curator_type', $itinerary->curator_type)
        ]);

        return $this->response->item($itinerary, new ItineraryTransformer);
    }

    public function destroy($id)
    {
        $itinerary = $this->itinerary->findOrFail($id);

        $itinerary->delete();

        return $this->response->noContent();
    }
}
