<?php

namespace App\Services\Importers;

class VisaListImportHandler extends ImportHandler
{

    /**
     * The model class to use
     *
     * @var string
     */
    public $model = 'App\Models\v1\Visa';

    /**
     * The database columns and document
     * columns to find matches on.
     * ['db_col' => 'doc_col']
     *
     * @var array
     */
    public $duplicates = ['number' => 'number'];

    public $matches = ['user.email' => 'user_email' ];


    public function match_columns_to_properties($visa)
    {
        return [
            'number' => $visa->number,
            'given_names' => $visa->given_names,
            'surname' => $visa->surname,
            'country_code' => strtolower(trim($visa->birth_country_code)),
            'issued_at' => $visa->issued_at,
            'expires_at' => $visa->expires_at,
            'created_at' => $visa->created_at,
            'updated_at' => $visa->updated_at
        ];
    }
}
