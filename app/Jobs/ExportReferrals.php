<?php

namespace App\Jobs;

use App\Models\v1\Referral;

class ExportReferrals extends Exporter
{
    public function data($request)
    {
        return Referral::filter($request)
            ->with('user')
            ->get();
    }

    public function columns($referral)
    {
        $data = [
            'id' => $referral->id,
            'applicant_name' => $referral->applicant_name,
            'type' => $referral->type,
            'status' => $referral->status,
            'sent_at' => $referral->sent_at ? $referral->sent_at->toDateTimeString() : null,
            'responded_at' => $referral->responded_at ? $referral->responded_at->toDateTimeString() : null,
            'user_id' => $referral->user_id,
            'user_name' => $referral->user->name,
            'user_email' => $referral->user->email,
            'user_phone' => $referral->user->phone,
            'created_at' => $referral->created_at->toDateTimeString(),
            'updated_at' => $referral->updated_at->toDateTimeString(),
        ];

        return $data;
    }
}
