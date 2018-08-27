<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Referral;
use Spatie\QueryBuilder\Filter;
use App\Http\Controllers\Controller;
use Dingo\Api\Contract\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\ReferralResource;
use App\Http\Requests\v1\CreateReferralRequest;
use App\Http\Requests\v1\UpdateReferralRequest;

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
        $referral = Referral::with(['user'])->findOrFail($id);

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
     * @param UpdateReferralRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(UpdateReferralRequest $request, $id)
    {
        $referral = Referral::findOrFail($id);

        $referral->update($request->all());

        $referral->attachToReservation($request->input('reservation_id'));

        return new ReferralResource($referral);
    }

    /**
     * Remove the specified referral from storage.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $referral = Referral::findOrFail($id);

        $referral->delete();

        return response()->json([], 204);
    }
}
