<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Referral;
use Spatie\QueryBuilder\Filter;
use App\Http\Controllers\Controller;
use Dingo\Api\Contract\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\ReferralResource;
use App\Http\Requests\v1\CreateReferralRequest;

class ReferralsController extends Controller
{
    /**
     * View a list of referrals.
     *
     * @return Response
     */
    public function index()
    {
        $referrals = QueryBuilder::for(Referral::class)
            ->allowedFilters([
                'applicant_name', 'attention_to', 'recipient_email',
                Filter::scope('status'),
                Filter::exact('type'),
                Filter::exact('user_id'),
                Filter::scope('managed_by')
            ])
            ->allowedIncludes(['user'])
            ->paginate(request()->input('per_page', 10));

        return ReferralResource::collection($referrals);
    }

    /**
     * View a specific referral.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $referral = Referral::findOrFail($id);

        return new ReferralResource($referral);
    }

    /**
     * Create a new referral and save in storage.
     *
     * @param ReferralRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(CreateReferralRequest $request)
    {
        $referral = Referral::create($request->all());

        $referral->attachToReservation($request->input('reservation_id'));

        return response()->json(['message' => 'Referral created.'], 201);
    }

    /**
     * Update the specified referral in storage.
     *
     * @param ReferralRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(UpdateReferralRequest $request, $id)
    {
        // $referral = $this->referral->findOrFail($id);

        // $referral->update([
        //     'user_id' => $request->get('user_id', $referral->user_id),
        //     'applicant_name' => $request->get('applicant_name', $referral->applicant_name),
        //     'attention_to' => $request->get('attention_to', $referral->attention_to),
        //     'recipient_email' => $request->get('recipient_email', $referral->recipient_email),
        //     'type' => $request->get('type', $referral->type),
        //     'referrer' => $request->get('referrer', $referral->referrer),
        //     'sent_at' => $request->get('sent_at', $referral->sent_at),
        //     'responded_at' => $request->get('responded_at', $referral->responded_at),
        //     'response' => $request->get('response', $referral->response)
        // ]);

        // return $this->response->item($referral, new ReferralTransformer);
    }

    /**
     * Remove the specified referral from storage.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        // $referral = $this->referral->findOrFail($id);

        // $referral->delete();

        // return $this->response->noContent();
    }
}
