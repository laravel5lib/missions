<?php

namespace App\Jobs;

use App\Models\v1\User;

class ExportUsers extends Exporter
{
    public function data(array $request)
    {
        // not working ???
        $users = User::filter($request)->get();

        return $users;
    }

    public function columns($user)
    {
        $columns = [
            'name' => $user->name,
            'email' => $user->email,
            'alt_email' => $user->alt_email,
            'gender' => $user->gender,
            'status' => $user->status,
            'birthday' => $user->birthday->toDateTimeString(),
            'phone_one' => $user->phone_one,
            'phone_two' => $user->phone_two,
            'address' => $user->address,
            'city' => $user->city,
            'state' => $user->state,
            'country_code' => $user->country_code,
            'timezone' => $user->timezone,
            'bio' => $user->bio,
            'visibility' => $user->public ? 'public' : 'private',
            'created_at' => $user->created_at->toDateTimeString(),
            'updated_at' => $user->updated_at->toDateTimeString()
        ];

        return $columns;
    }
}
