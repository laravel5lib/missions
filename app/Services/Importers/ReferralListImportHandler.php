<?php

namespace App\Services\Importers;

class ReferralListImportHandler extends ImportHandler {

    /**
     * The model class to use
     * 
     * @var string
     */
    public $model = 'App\Models\v1\Referral';

    /**
     * The database columns and document 
     * columns to find matches on.
     * ['db_col' => 'doc_col']
     * 
     * @var array
     */
    public $duplicates = ['name' => 'name', 'referral_email' => 'referral_email'];

    public $matches = ['user.email' => 'user_email' ];


    public function match_columns_to_properties($referral)
    {
        return [
            'name' => $referral->name,
            'referral_name' => $referral->referral_name,
            'referral_email' => $referral->email,
            'referral_phone' => $referral->phone,
            'status' => $referral->status,
            'sent_at' => $referral->sent_at,
            'created_at' => $referral->created_at,
            'updated_at' => $referral->updated_at
        ];
    }

}