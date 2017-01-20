<?php

namespace App\Services\Importers;

class GroupListImportHandler extends ImportHandler {

    /**
     * The model class to use
     * 
     * @var string
     */
    public $model = 'App\Models\v1\Group';

    /**
     * The database columns and document 
     * columns to find matches on.
     * ['db_col' => 'doc_col']
     * 
     * @var array
     */
    public $duplicates = ['name' => 'name'];

    public function match_columns_to_properties($group)
    {
        return [
            'name' => $group->name,
            'created_at' => $group->created_at,
            'updated_at' => $group->updated_at
        ];
    }

}