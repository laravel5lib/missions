<?php

namespace App\Services\Importers;

class PassportListImportHandler extends ImportHandler
{

    /**
     * The model class to use
     *
     * @var string
     */
    public $model = 'App\Models\v1\Passport';

    /**
     * The database columns and document
     * columns to find matches on.
     * ['db_col' => 'doc_col']
     *
     * @var array
     */
    public $duplicates = ['number' => 'number'];

    public $matches = ['user.email' => 'user_email' ];


    public function match_columns_to_properties($passport)
    {
        return [
            'number' => $passport->number,
            'given_names' => $passport->given_names,
            'surname' => $passport->surname,
            'birth_country' => strtolower(trim($passport->birth_country_code)),
            'citizenship' => strtolower(trim($passport->citizenship_country_code)),
            'issued_at' => $passport->issued_at,
            'expires_at' => $passport->expires_at,
            'created_at' => $passport->created_at,
            'updated_at' => $passport->updated_at
        ];
    }
}
