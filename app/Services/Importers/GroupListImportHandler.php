<?php

namespace App\Services\Importers;

use App\Models\v1\User;

class GroupListImportHandler extends ImportHandler
{

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
    public $duplicates = ['name' => 'name', 'type' => 'type'];

    public function match_columns_to_properties($group)
    {
        $group = $group->transform(function ($item) {
            return trim($item);
        });

        return [
            'name' => ucwords($group->name),
            'type' => strtolower(str_replace('-', '', $group->type)),
            'description' => $group->description,
            'public' => $group->visibility == 'public' ? true : false,
            'timezone' => $group->timezone,
            'address_one' => $group->address_one,
            'address_two' => $group->address_two,
            'city' => $group->city,
            'state' => $group->state,
            'zip' => $group->zip,
            'country_code' => $group->country_code ? strtolower($group->country_code) : 'us',
            'phone_one' => $group->phone_one,
            'phone_two' => $group->phone_two,
            'email' => $group->email,
            'status' => $group->status,
            // 'avatar_upload_id' => $this->attach_avatar($group),
            // 'banner_upload_id' => $this->attach_banner($group),
            'created_at' => $group->created_at,
            'updated_at' => $group->updated_at,
            'managers' => $this->get_managers_by_email($group->manager_emails),
            'slug' => [
                'url' => $group->url ? $group->url : generate_slug($group->name)
            ],
            'links' => $this->get_links($group)
        ];
    }

    private function get_managers_by_email($emails)
    {
        $emails = is_array($emails) ? $emails : explode(',', $emails);

        $userIds = User::whereIn('email', $emails)->pluck('id')->all();

        return $userIds;

        // return $userIds->map(function($id) {
        //     return ['user_id' => $id];
        // })->all();
    }

    private function attach_avatar($group)
    {
        if (! $group->logo_source) {
            return null;
        }

        $upload = Upload::firstOrNew(['source' => trim($group->logo_source)]);

        $upload->name = trim($group->name) . ' Logo';
        $upload->type = 'avatar';
        $upload->source = trim($group->logo_source);
        $avatar = $upload->save();

        return $avatar->id;
    }

    private function attach_banner($group)
    {
        if (! $group->banner_source) {
            return null;
        }

        $upload = Upload::firstOrNew(['source' => trim($group->banner_source)]);

        if ($upload->id) {
            return $upload->id;
        }

        return null;
    }

    private function get_links($group)
    {
        $links = $group->filter(function ($value, $key) {
            return ends_with($key, '_url');
        })->map(function ($value, $key) {
            return [
                'name' => chop($key, '_url'),
                'url' => remove_http($value)
            ];
        })->all();

        return $links;
    }
}
