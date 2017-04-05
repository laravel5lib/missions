<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Http\Requests;
use App\Models\v1\Itinerary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\ItineraryItemRequest;
use App\Http\Transformers\v1\ItineraryItemTransformer;

class ItineraryItemsController extends Controller
{
    private $itinerary;

    function __construct(Itinerary $itinerary)
    {
        $this->itinerary = $itinerary;
    }

    public function index($itineraryId)
    {
        $items = $this->itinerary
                      ->findOrFail($itineraryId)
                      ->items()
                      ->paginate(10);

        return $this->response->paginator($items, new ItineraryItemTransformer);
    }

    public function show($itineraryId, $itemId)
    {
        $item = $this->itinerary
                     ->findOrFail($itineraryId)
                     ->items()
                     ->findOrFail($itemId);

        return $this->response->item($item, new ItineraryItemTransformer);
    }

    public function store($itineraryId, ItineraryItemRequest $request)
    {
        $items = $this->itinerary
                      ->findOrFail($itineraryId)
                      ->items();

        $item = $items->create([
          'title' => $request->get('title'),
          'description' => $request->get('description'),
          'type' => $request->get('type', 'event'),
          'occurs_at' => $request->get('occurs_at', Carbon::now())
        ]);

        return $this->response->item($item, new ItineraryItemTransformer);
    }

    public function update($itineraryId, $itemId, ItineraryItemRequest $request)
    {
        $item = $this->itinerary
                     ->findOrFail($itineraryId)
                     ->items()
                     ->findOrFail($itemId);

        $item->update([
          'title' => $request->get('title', $item->title),
          'description' => $request->get('description', $item->description),
          'type' => $request->get('type', $item->type),
          'occurs_at' => $request->get('occurs_at', $item->occurs_at)
        ]);

        if ($request->get('attachments')) {
          $item->addAttachment($request->get('attachments'));
        }

        return $this->response->item($item, new ItineraryItemTransformer);
    }

    public function destroy($itineraryId, $itemId)
    {
        $item = $this->itinerary
                     ->findOrFail($itineraryId)
                     ->items()
                     ->findOrFail($itemId);

        $item->delete();

        return $this->response->noContent();
    }
}
