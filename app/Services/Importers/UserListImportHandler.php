<?php

namespace App\Services\Importers;

use App\Models\v1\Upload;

class UserListImportHandler extends ImportHandler
{

    /**
     * The model class to use
     *
     * @var string
     */
    public $model = 'App\Models\v1\User';

    /**
     * The database columns and document
     * columns to find matches on.
     *
     * @var array
     */
    public $duplicates = ['email' => 'email'];


    public function match_columns_to_properties($user)
    {
        $user = $user->transform(function ($item) {
            return trim($item);
        });

        return [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password ? $user->password : str_random(10),
            'alt_email' => $user->alt_email,
            'gender' => strtolower($user->gender),
            'status' => strtolower($user->status),
            'birthday' => $user->birthday,
            'phone_one' => $user->phone_one,
            'phone_two' => $user->phone_two,
            'address' => $user->address,
            'city' => $user->city,
            'state' => $user->state,
            'zip' => $user->zip,
            'country_code' => $user->country_code ? strtolower($user->country_code) : 'us',
            'timezone' => $user->timezone,
            'bio' => $user->bio,
            'public' => $user->visibility == 'public' ? true : false,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            // 'avatar_upload_id' => $this->attach_avatar($user),
            // 'banner_upload_id' => $this->attach_banner($user),
            'slug' => [
                'url' => $user->url ? $user->url : generate_slug($user->name)
            ],
            'links' => $this->get_links($user)
        ];
    }

    private function attach_avatar($user)
    {
        if (! $user->logo_source) {
            return null;
        }

        $upload = Upload::firstOrNew(['source' => trim($user->avatar_source)]);

        $upload->name = trim($user->name) . ' Logo';
        $upload->type = 'avatar';
        $upload->source = trim($user->logo_source);
        $avatar = $upload->save();

        return $avatar->id;
    }

    private function attach_banner($user)
    {
        if (! $user->banner_source) {
            return null;
        }

        $upload = Upload::firstOrNew(['source' => trim($user->banner_source)]);

        if ($upload->id) {
            return $upload->id;
        }

        return null;
    }

    private function get_links($user)
    {
        $links = $user->filter(function ($value, $key) {
            return ends_with($key, '_url') && $value;
        })->map(function ($value, $key) {
            return [
                'name' => chop($key, '_url'),
                'url' => remove_http($value)
            ];
        })->all();

        return $links;
    }
}
