<?php

namespace App\Services\Importers;

class UserListImportHandler extends ImportHandler {

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
    public $match = ['email' => 'email'];


    public function match_columns_to_properties($user)
    {
        return [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password ? $user->password : 'password',
            'alt_email' => $user->alt_email,
            'gender' => $user->gender,
            'status' => $user->status,
            'birthday' => $user->birthday,
            'phone_one' => $user->phone_one,
            'phone_two' => $user->phone_two,
            'address' => $user->address,
            'city' => $user->city,
            'state' => $user->state,
            'zip' => $user->zip,
            'country_code' => $user->country_code,
            'timezone' => $user->timezone,
            'bio' => $user->bio,
            'public' => $user->visibility == 'public' ? true : false,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'slug' => [
                'url' => $user->url ? $user->url : generate_slug($user->name)
            ]
        ];
    }

}