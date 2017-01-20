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
    public $duplicates = [];

    public $matches = [];

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
            return $this->find_existing($this->get_duplicates($item));
        })->filter(function($item) {
            return $this->find_matching($item);
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
     * Get duplicates.
     * 
     * @param  Collection $item
     * @return array
     */
    private function get_duplicates($item)
    {   
        $duplicates = [];

        foreach($this->duplicates as $databaseColumn => $documentColumn)
        {
            $duplicates[$databaseColumn] = $item->{$documentColumn};
        }

        return $duplicates;
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
     * Find matching
     * 
     * @param  Collection $item
     * @return Boolean
     */
    private function find_matching($item)
    {   
        $results = [];
        $matches = [];

        foreach($this->matches as $property => $documentColumn)
        {
            if (strpos($property, '.')) {
                $properties = explode('.', $property);

                $method = $properties[0];
                $attribute = $properties[1];

                $model = app($this->model)->whereHas($method, function($m) use($attribute, $item, $documentColumn) {
                    return $m->where($attribute, $item->{$documentColumn});
                })->first();

                $results[] = $model ? true : false;

            } else {

                $matches[$property] = $item->{$documentColumn};
            }
        }

        if ($matches <> []) {
            $model = app($this->model)->firstOrNew($matches);

            $results[] = $model->id ? true : false;
        }

        return collect($results)->contains(false) ? false : true;
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
        if ($this->check_for_missing_columns($import))
            return abort(422, 'Missing required fields');

        if ($this->check_for_missing_values($import))
            return abort(422, 'Some required fields are blank');

        return true;
    }

    private function check_for_missing_columns($import)
    {
        $required = Request::get('required');

        $numberOfColumns = $import->select($required)->get()->first()->count();

        return ($numberOfColumns != count($required)) ? true : false;
    }

    private function check_for_missing_values($import)
    {
        $required = Request::get('required');

        $missingValues = $import->select($required)->get()->filter(function($item) {
            $item = collect($item);
            return $item->contains(null) || $item->contains('');
        })->count();

        return ($missingValues > 0) ? true : false;
    }

}