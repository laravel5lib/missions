<?php

namespace App\Services;

use App\Models\v1\Group;

class GroupListImportHandler implements \Maatwebsite\Excel\Files\ImportHandler {

    public function handle($import)
    { 
        $import->chunk(250, function($groups)
        {
            $groups->map(function($group) {
                return $this->match_columns($group);
            })->each(function($group) {
                $this->save_group($group);
            })->count();
        });

        // send completion email;
    }

    private function save_group($group)
    {
        $g = Group::firstOrNew(['name' => $group['name']]);
        
        if ( ! $g->id) $u->create($group);
    }

    private function match_columns($group)
    {
        return [
            'name' => $group->name,
            'email' => $group->email,
            'status' => $group->status,
            'type' => $group->type,
            'phone_one' => $group->phone_one,
            'phone_two' => $group->phone_two,
            'address_one' => $group->address_one,
            'address_two' => $group->address_two,
            'city' => $group->city,
            'state' => $group->state,
            'zip' => $group->zip,
            'country_code' => $group->country_code,
            'timezone' => $group->timezone,
            'description' => $group->description,
            'public' => $group->visibility == 'public' ? true : false,
            'created_at' => $group->created_at,
            'updated_at' => $group->updated_at,
        ];
    }

}