<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\v1\Referral;
use App\Jobs\ExportReferrals;
use App\Http\Controllers\Controller;
use Dingo\Api\Contract\Http\Request;
use App\Jobs\SendReferralRequestEmail;
use App\Http\Requests\v1\ExportRequest;
use App\Http\Requests\v1\ImportRequest;
use App\Http\Requests\v1\ReferralRequest;
use App\Services\Importers\ReferralListImport;
use App\Http\Transformers\v1\ReferralTransformer;

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
        $referral = $this->referral->create([
            'user_id' => $request->get('user_id'),
            'applicant_name' => $request->get('applicant_name'),
            'attention_to' => $request->get('attention_to'),
            'recipient_email' => $request->get('recipient_email'),
            'type' => $request->get('type'),
            'referrer' => $request->get('referrer'),
            'response' => $request->get('response'),
            'sent_at' => Carbon::now(),
            'responded_at' => null
        ]);

        dispatch(new SendReferralRequestEmail($referral));

        return $this->response->item($referral, new ReferralTransformer);
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
            'applicant_name' => $request->get('applicant_name', $referral->applicant_name),
            'attention_to' => $request->get('attention_to', $referral->attention_to),
            'recipient_email' => $request->get('recpient_email', $referral->recipient_email),
            'type' => $request->get('type', $referral->type),
            'referrer' => $request->get('referrer', $referral->referrer),
            'sent_at' => $request->get('sent_at', $referral->sent_at),
            'responded_at' => $request->get('responded_at', $referral->responded_at),
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

    /**
     * Import Referrals.
     * 
     * @param  ImportRequest      $request 
     * @param  ReferralListImport $import  
     * @return response                      
     */
    public function import(ImportRequest $request, ReferralListImport $import)
    {
        $response = $import->handleImport();

        return $this->response()->created(null, $response);
    }

}
