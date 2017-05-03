<?php

namespace App\Http\Controllers\Api;

use App\Http\Transformers\v1\AirportTransformer;
use App\Models\v1\Airport;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AirportController extends Controller
{
    /**
     * @var Airport
     */
    private $airport;

    /**
     * Instantiate a new AirportsController instance.
     * @param Airport $airport
     */
    public function __construct(Airport $airport)
    {
        $this->airport = $airport;
    }

    /**
     * Get all airports.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $airports = $this->airport->filter($request->all())->paginate($request->get('per_page', 50));
        return $this->response->paginator($airports, new AirportTransformer);
    }

    /**
     * Show the specified airport.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $airport = $this->airport->whereId($id)->orWhere('name', $id)->orWhere('iata', $id)->firstOrFail();
        return $this->response->item($airport, new AirportTransformer);
    }
}
