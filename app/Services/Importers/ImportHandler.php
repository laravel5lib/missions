<?php

namespace App\Services\Importers;

use App\Jobs\SendImportFinishedEmail;
use Illuminate\Support\Facades\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ImportHandler implements \Maatwebsite\Excel\Files\ImportHandler
{

    use DispatchesJobs;

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

        $totalRows = $import->get()->count();
        
        $import->chunk(250, function ($results) {
            $results->reject(function ($item) {
                return $this->find_existing($item);
            })->map(function ($item) {
                return $this->match_columns_to_properties($item);
            })->each(function ($item) {
                $this->save_new($item);
            });
        }, false);

        $job = (new SendImportFinishedEmail(
            $email = Request::get('email'),
            $records = $totalRows,
            $list = $this->model
        ))
                    ->onQueue('import')
                    ->delay(60 * 5); // 5 minutes

        $this->dispatch($job);

        // $totalImported = $import->get()->reject(function($item) {
        //     return $this->find_existing($this->get_duplicates($item));
        // })->filter(function($item) {
        //     return $this->find_matching($item);
        // })->map(function($item) {
        //     return $this->match_columns_to_properties($item);
        // })->each(function($item) {
        //     $this->save_new($item);
        // })->count();

        return [
            'total_rows' => $totalRows,
            // 'total_imported' => $totalImported,
            'message' => 'File processed. You will be notified when finished importing.'
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

        foreach ($this->duplicates as $databaseColumn => $documentColumn) {
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
    // private function find_existing($attributes)
    // {
    //     $item = app($this->model)->firstOrNew($attributes);

    //     return $item->id ? true : false;
    // }

    /**
     * Find existing
     *
     * @param  Collection $item
     * @return Boolean
     */
    private function find_existing($item)
    {
        $results = [];
        $matches = [];

        foreach ($this->duplicates as $property => $documentColumn) {
            if (strpos($property, '.')) {
                $properties = explode('.', $property);

                $method = $properties[0];
                $attribute = $properties[1];

                $model = app($this->model)->whereHas($method, function ($m) use ($attribute, $item, $documentColumn) {
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
        $model = $this->save_properties($item);

        $this->save_relations($model, $item);

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
     * Save properties to the model.
     *
     * @param  Array $item
     * @return Object $model
     */
    private function save_properties($item)
    {
        $model = app($this->model);

        $data = collect($item)->reject(function ($i, $key) use ($model) {
            return is_array($i) && method_exists($model, $key);
        })->all();

        $newModel = $model->create($data);

        return $newModel;
    }

    /**
     * Save related models.
     *
     * @param  Array $item
     * @return Array $item
     */
    private function save_relations($model, $item)
    {
        $relations = collect($item)->filter(function ($i, $key) use ($model) {
            return is_array($i) && method_exists($model, $key);
        })->each(function ($data, $method) use ($model) {

            // if relationship is a list
            $isArray = array_first($data, function ($key, $value) {
                return is_array($value);
            });

            if ($isArray) {
                collect($data)->each(function ($item) use ($model, $method) {

                    $related = class_basename(get_class($model->{$method}()));

                    if ($related == 'BelongsToMany') {
                        $model->{$method}()->attach($item);
                    } else {
                        $model->{$method}()->create($item);
                    }
                });
            } else {
                // if relationship is an item
                $related = class_basename(get_class($model->{$method}()));

                if ($related == 'BelongsToMany') {
                    $model->{$method}()->attach($data);
                } else {
                    $model->{$method}()->create($data);
                }
            }
        });

        return $item;
    }

    /**
     * Validate the imported file.
     *
     * @param  Collection $import
     * @return Mixed
     */
    private function validate($import)
    {
        if ($this->check_for_missing_columns($import)) {
            return abort(422, 'Missing required fields');
        }

        if ($this->check_for_missing_values($import)) {
            return abort(422, 'Some required fields are blank');
        }

        return true;
    }

    /**
     * Check for missing required columns.
     *
     * @param  Object $import
     * @return Boolean
     */
    private function check_for_missing_columns($import)
    {
        $required = Request::get('required');

        $numberOfColumns = $import->get()->first()->only($required)->count();

        return ($numberOfColumns != count($required)) ? true : false;
    }

    /**
     * Check for missing values in each required column.
     *
     * @param  Object $import
     * @return Boolean
     */
    private function check_for_missing_values($import)
    {
        $required = Request::get('required');

        $missingValues = $import->get()->only($required)->filter(function ($item) {
            $item = collect($item);
            return $item->contains(null) || $item->contains('');
        })->count();

        return ($missingValues > 0) ? true : false;
    }
}
