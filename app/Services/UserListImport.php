<?php

namespace App\Services;

class UserListImport extends \Maatwebsite\Excel\Files\ExcelFile {

    protected $delimiter  = ',';
    protected $enclosure  = '"';
    protected $lineEnding = '\r\n';


    public function getFile()
    {
        return storage_path('exports') . '/users_1484683738.csv';
    }

    public function getFilters()
    {
        return [
            'chunk'
        ];
    }

}