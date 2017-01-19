<?php

namespace App\Services\Importers;

use Illuminate\Support\Facades\Request;

class ImportHandler implements \Maatwebsite\Excel\Files\ImportHandler {

    /**
     * The model class to use
     * 
     * @var string
     */
    public $model;

    /**
     * The database columns and document 
     * columns to find matches on.
     * 
     * @var array
     */
    public $match = [];

    /**
     * Handle the import
     * 
     * @param  Collection $import
     * @return response
     */
    public function handle($import)
    { 
        $this->validate($import);

        $totalRows = $import->get()->count() - 1; // -1 for header row

        $totalImported = $import->get()->reject(function($item) {
            return $this->find_existing($this->get_matches($item));
        })->map(function($item) {
            return $this->match_columns_to_properties($item);
        })->each(function($item) {
            return $this->save_new($item);
        })->count();

        return [
            'total_rows' => $totalRows, 
            'total_imported' => $totalImported, 
            'message' => 'Import complete.'
        ];
    }

    /**
     * Get column matches.
     * 
     * @param  Collection $item
     * @return array
     */
    private function get_matches($item)
    {   
        $matches = [];

        foreach($this->match as $databaseColumn => $documentColumn)
        {
            $matches[$databaseColumn] = $item->{$documentColumn};
        }

        return $matches;
    }

    /**
     * Find existing to prevent duplicates.
     * 
     * @param  Array $attributes
     * @return Boolean
     */
    private function find_existing($attributes)
    {
        $item = app($this->model)->firstOrNew($attributes);

        return $item->id ? true : false;
    }

    /**
     * Save New Item
     * 
     * @param  Array $item
     * @return response
     */
    private function save_new($item)
    {   
        $data = collect($item)->reject(function($i) {
            return is_array($i);
        })->all();

        $model = app($this->model)->create($data);

        $relations = collect($item)->filter(function($i) {
            return is_array($i);
        })->each(function($data, $method) use($model) {
            $model->{$method}()->create($data);
        });

        return $item;
    }

    /**
     * Match document columns with model properties.
     * 
     * @param  Collection $item
     * @return Array
     */
    public function match_columns_to_properties($item)
    {
        // Follow this format:

        // [ 
        //      'property' => $collection->value,
        //      'relation' => [
        //          'property' => $collection->value
        //      ]
        // ];
        
        return [];
    }

    /**
     * Validate the imported file.
     * 
     * @param  Collection $import
     * @return Mixed
     */
    private function validate($import)
    {
        $missingValues = $import->select(Request::get('required'))->get()->filter(function($item) {
            $item = collect($item);
            return $item->contains(null) || $item->contains('');
        })->count();

        return $missingValues > 0 ? abort(422, 'Missing required fields') : true;
    }

}