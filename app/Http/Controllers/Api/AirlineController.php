<?php

namespace App\Http\Controllers\Api;

use App\Http\Transformers\v1\AirlineTransformer;
use App\Models\v1\Airline;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AirlineController extends Controller
{
    /**
     * @var Airline
     */
    private $airline;

    /**
     * Instantiate a new AirlinesController instance.
     * @param Airline $airline
     */
    public function __construct(Airline $airline)
    {
        $this->airline = $airline;
    }

    /**
     * Get all airlines.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $airlines = $this->airline->filter($request->all())->paginate($request->get('per_page', 50));
        return $this->response->paginator($airlines, new AirlineTransformer);
    }

    /**
     * Show the specified airline.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $airline = $this->airline->whereId($id)->orWhere('name', $id)->orWhere('iata', $id)->firstOrFail();
        return $this->response->item($airline, new AirlineTransformer);
    }
}
