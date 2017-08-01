<?php

namespace App\Services\Importers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Request;

class ListImport extends \Maatwebsite\Excel\Files\ExcelFile
{

    /**
     * The default directory path where
     * the imported file should live.
     *
     * @var string
     */
    public $path = 'imports';

    /**
     * The default name for the imported file.
     *
     * @var string
     */
    public $filename = 'import';

    /**
     * The default format for the imported file.
     *
     * @var string
     */
    public $filetype = '.csv';

    /**
     * Default delimiter for .csv
     *
     * @var string
     */
    protected $delimiter  = ',';

    /**
     * Default enclosure for .csv
     *
     * @var string
     */
    protected $enclosure  = '"';

    /**
     * Default line ending for .csv
     *
     * @var string
     */
    protected $lineEnding = '\r\n';

    /**
     * Get the uploaded file.
     *
     * @return String
     */
    public function getFile()
    {
        $file = Request::get('file');

        $source = $this->store_file($file);

        return storage_path('app/'.$source);
    }

    /**
     * Put the file in storage.
     *
     * @param  String $file
     * @return String fully qualified path
     */
    public function store_file($file)
    {
        $source = $this->path.'/'.$this->filename.'_'.time().$this->filetype;

        Storage::disk('local')
            ->put($source, fopen($file, 'r+'));

        return $source;
    }

    /**
     * Remove the file from storage.
     * @param  String $source
     * @return null
     */
    public function remove_file($source)
    {
        Storage::disk('local')->delete($source);
    }

    /**
     * Get any filters that can be
     * used by the import handler.
     *
     * @return Array
     */
    public function getFilters()
    {
        return [
            'chunk'
        ];
    }
}
