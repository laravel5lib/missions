<?php

namespace App\Services\Importers;

use App\Models\v1\User;

class MedicalReleaseListImportHandler extends ImportHandler
{

    /**
     * The model class to use
     *
     * @var string
     */
    public $model = 'App\Models\v1\MedicalRelease';

    /**
     * The database columns and document
     * columns to find matches on.
     *
     * @var array
     */
    public $duplicates = ['name' => 'name'];

    public $matches = ['user.email' => 'user_email' ];


    public function match_columns_to_properties($release)
    {
        $details = collect([
            'name' => $release->name,
            'ins_provider' => $release->ins_provider,
            'ins_policy_no' => $release->ins_policy_no,
            'emergency_contact' => [
                'name' => $release->emergency_name,
                'phone' => $release->emergency_phone,
                'email' => $release->emergency_email
            ],
            'user_id'    => User::where('email', $release->user_email)->firstOrFail()->id,
            'created_at' => $release->created_at,
            'updated_at' => $release->updated_at
        ]);

        $conditions = $this->map_items($release->conditions);

        $allergies = $this->map_items($release->allergies);

        return $details->put('conditions', $conditions)
                       ->put('allergies', $allergies);
    }

    private function map_items($items)
    {
        return collect(explode(',', $items))->reject(function ($item) {
            $item = collect($item);
            return $item->contains(null) || $item->contains('');
        })->map(function ($item) {
            return [
                'name' => trim(explode('(', $item)[0]),
                'diagnosed' => str_contains($item, 'Diagnosed') ? true : false,
                'medication' => str_contains($item, 'Medication') ? true : false,
            ];
        })->all();
    }
}
