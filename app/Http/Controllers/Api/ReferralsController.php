<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\ExportRequest;
use App\Http\Requests\v1\ReferralRequest;
use App\Http\Transformers\v1\ReferralTransformer;
use App\Jobs\ExportReferrals;
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
        $referrals = $this->referral
                        ->filter($request->all())
                        ->paginate($request->get('per_page', 10));

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
        $referral = Referral::create([
            'user_id' => $request->get('user_id'),
            'name' => $request->get('name'),
            'type' => $request->get('type'),
            'referral_name' => $request->get('referral_name'),
            'referral_email' => $request->get('referral_email'),
            'referral_phone' => $request->get('referral_phone'),
            'status' => 'sent',
            'sent_at' => Carbon::now()
        ]);

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

        $referral->update([
            'user_id' => $request->get('user_id', $referral->user_id),
            'name' => $request->get('name', $referral->name),
            'type' => $request->get('type', $referral->type),
            'referral_name' => $request->get('referral_name', $referral->referral_name),
            'referral_email' => $request->get('referral_email', $referral->referral_email),
            'referral_phone' => $request->get('referral_phone', $referral->referral_phone),
            'status' => $request->get('status', $referral->status),
            'sent_at' => $request->get('sent_at', $referral->sent_at),
            'response' => $request->get('response', $referral->response)
        ]);

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

    /**
     * Export referrals.
     *
     * @param ExportRequest $request
     * @return mixed
     */
    public function export(ExportRequest $request)
    {
        $this->dispatch(new ExportReferrals($request->all()));

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly.'
        ]);
    }

}
