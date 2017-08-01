<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Referral;
use League\Fractal\TransformerAbstract;

class ReferralTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'user'
    ];

    /**
     * Transform the object into a basic array
     *
     * @param Referral $referral
     * @return array
     */
    public function transform(Referral $referral)
    {
        return [
            'id' => $referral->id,
            'user_id' => $referral->user_id,
            'applicant_name' => $referral->applicant_name,
            'type' => $referral->type,
            'attention_to' => $referral->attention_to,
            'recipient_email' => $referral->recipient_email,
            'referrer' => $referral->referrer,
            'response' => $referral->response,
            'status' => $referral->status,
            'sent_at' => $referral->sent_at ? $referral->sent_at->toDateTimeString() : null,
            'responded_at' => $referral->responded_at ? $referral->responded_at->toDateTimeString() : null,
            'created_at' => $referral->created_at->toDateTimeString(),
            'updated_at' => $referral->updated_at->toDateTimeString(),
            'links'        => [
                [
                    'rel' => 'self',
                    'uri' => 'api/referrals/' . $referral->id,
                ]
            ]
        ];
    }

    /**
     * Include User
     *
     * @param Referral $referral
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Referral $referral)
    {
        $response = $referral->user;

        return $this->item($response, new UserTransformer);
    }
}
