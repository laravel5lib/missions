<?php

namespace App\Http\Controllers\Api;

use App\Http\Transformers\v1\TripInterestTransformer;
use App\Models\v1\TripInterest;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TripInterestsController extends Controller
{

    /**
     * @var TripInterest
     */
    private $interest;

    /**
     * TripInterestsController constructor.
     * @param TripInterest $interest
     */
    public function __construct(TripInterest $interest)
    {
        $this->interest = $interest;
    }

    public function index(Request $request)
    {
        $interests = $this->interest->filter($request->all())->paginate();

        return $this->response->paginator($interests, new TripInterestTransformer);
    }

    public function show($id)
    {
        $interest = $this->interest->findOrFail($id);

        return $this->response->item($interest, new TripInterestTransformer);
    }
}
