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
        return [
            'id' => $referral->id,
            'user_id' => $referral->user_id,
            'name' => $referral->name,
            'type' => $referral->type,
            'referral_name' => $referral->referral_name,
            'referral_email' => $referral->referral_email,
            'referral_phone' => $referral->referral_phone,
            'status' => $referral->status,
            'sent_at' => $referral->sent_at->toDateTimeString(),
            'created_at' => $referral->created_at->toDateTimeString(),
            'updated_at' => $referral->updated_at->toDateTimeString(),
            'user_name' => $referral->user->name,
            'user_email' => $referral->user->email,
            'user_phone' => $referral->user->phone,
        ];
    }
}
