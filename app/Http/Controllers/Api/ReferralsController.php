<?php

namespace App\Http\Controllers\v1;

use App\Http\Requests;
use App\Http\Requests\v1\ReferralRequest;
use App\Http\Transformers\v1\ReferralTransformer;
use App\Models\v1\Referral;
use Carbon\Carbon;
use Dingo\Api\Contract\Http\Request;

class ReferralsController extends Controller
{

    /**
     * @var Referral
     */
    private $referral;

    /**
     * ReferralsController constructor.
     * @param Referral $referral
     */
    public function __construct(Referral $referral)
    {
        $this->middleware('api.auth');
        $this->middleware('jwt.refresh');
        $this->referral = $referral;
    }

    /**
     * Get all referrals.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $referrals = $this->referral;

        if($request->has('user_id'))
            $referrals = $referrals->where('user_id', $request->get('user_id'));

        $referrals = $referrals->paginate($request->get('per_page', 25));

        return $this->response->paginator($referrals, new ReferralTransformer);
    }

    /**
     * Get the specified referral.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $referral = $this->referral->findOrFail($id);

        return $this->response->item($referral, new ReferralTransformer);
    }

    /**
     * Create a new referral and save in storage.
     *
     * @param ReferralRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(ReferralRequest $request)
    {
        $referral = new Referral($request->all);
        $referral->sent_at = Carbon::now();
        $referral->save();

        // generate a url to response form
        // email url to referral_email

        $location = url('/referrals/' . $referral->id);

        return $this->response->created($location);
    }

    /**
     * Update the specified referral in storage.
     *
     * @param ReferralRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(ReferralRequest $request, $id)
    {
        $referral = $this->referral->findOrFail($id);

        $referral->update($request->all());

        return $this->response->item($referral, new ReferralTransformer);
    }

    /**
     * Remove the specified referral from storage.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $referral = $this->referral->findOrFail($id);

        $referral->delete();

        return $this->response->noContent();
    }
}
