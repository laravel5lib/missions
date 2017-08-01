<?php

namespace App\Services\Importers;

class EssayListImportHandler extends ImportHandler
{

    /**
     * The model class to use
     *
     * @var string
     */
    public $model = 'App\Models\v1\Essay';

    /**
     * The database columns and document
     * columns to find matches on.
     * ['db_col' => 'doc_col']
     *
     * @var array
     */
    public $duplicates = ['author_name' => 'author_name', 'subject' => 'subject'];

    public $matches = ['user.email' => 'user_email' ];


    public function match_columns_to_properties($referral)
    {
        return [
            'author_name' => $referral->author_name,
            'subject' => $referral->subject,
            'content' => is_array($referral->content) ? $referral->content : [ $referral->content ],
            'created_at' => $referral->created_at,
            'updated_at' => $referral->updated_at
        ];
    }
}
