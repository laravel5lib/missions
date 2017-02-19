<?php

namespace App\Jobs;

use App\Models\v1\Group;

class ExportGroups extends Exporter
{
    public function data($request)
    {
        return Group::filter($request)
                ->with('slug', 'avatar', 'banner')
                ->get();
    }

    public function columns($group)
    {
        return [
            'name' => $group->name,
            'type' => $group->type,
            'description' => $group->description,
            'visibility' => $group->public ? 'Public' : 'Private',
            'timezone' => $group->timezone,
            'address' => $group->address_one.' '.$group->address_two,
            'city' => $group->city,
            'state' => $group->state,
            'zip' => $group->zip,
            'country' => country($group->country_code),
            'country_code' => $group->country_code,
            'primary_phone' => $group->phone_one,
            'secondary_phone' => $group->phone_two,
            'email' => $group->email,
            'status' => $group->status,
            'avatar_source' => $group->avatar ? $group->avatar->source : null,
            'banner_source' => $group->banner ? $group->banner->source : null,
            'created' => $group->created_at->toDateTimeString(),
            'updated' => $group->updated_at->toDateTimeString(),
            'url' => $group->slug ? $group->slug->url : null
        ];
    }
}
