<?php

namespace App\Jobs;

use App\Models\v1\MedicalRelease;

class ExportMedicalReleases extends Exporter
{
    public function data(array $request)
    {
        return MedicalRelease::filter($request)
                ->with('user', 'conditions', 'allergies')
                ->get();
    }

    public function columns($release)
    {
        $details = collect([
            'name' => $release->name,
            'ins_provider' => $release->ins_provider,
            'ins_policy_no' => $release->ins_policy_no,
            'emergency_name' => isset($release->emergency_contact['name']) ? 
                                    $release->emergency_contact['name'] : null,
            'emergency_phone' => isset($release->emergency_contact['phone']) ? 
                                    $release->emergency_contact['phone'] : null,
            'emergency_email' => isset($release->emergency_contact['email']) ? 
                                    $release->emergency_contact['email'] : null
        ]);

        $conditions = $release->conditions->map(function($condition) {
            return $condition->name . 
                   ($condition->diagnosed ? ' (Diagnosed)' : ''). 
                   ($condition->medication ? ' (Medication)' : '');
        })->all();

        $allergies = $release->allergies->map(function($allergy) {
            return $allergy->name . 
                   ($allergy->diagnosed ? ' (Diagnosed)' : ''). 
                   ($allergy->medication ? ' (Medication)' : '');
        })->all();

        $user = collect([
            'user_name' => $release->user->name,
            'user_email' => $release->user->email,
            'user_phone_one' => $release->user->phone_one,
            'user_phone_two' => $release->user->phone_two,
            'created_at' => $release->created_at->toDateTimeString(),
            'updated_at' => $release->updated_at->toDateTimeString()
        ]);

        return $details->put('conditions', implode(', ', $conditions))
                       ->put('allergies', implode(', ', $allergies))
                       ->merge($user);
    }
}
