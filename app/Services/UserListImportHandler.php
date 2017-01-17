<?php

namespace App\Services;

use App\Models\v1\User;

class UserListImportHandler implements \Maatwebsite\Excel\Files\ImportHandler {

    public function handle($import)
    { 
        $this->validate($import);

        $import->chunk(250, function($users)
        {
            $users->map(function($user) {
                return $this->match_columns($user);
            })->each(function($user) {
                $this->save_user($user);
            })->count();
        });

        // send completion email;
    }

    private function validate($import)
    {
        if ($import->first()
               ->diffKeys(collect([
                    'name', 'email', 'password', 'alt_email', 'gender', 'status',
                    'birthday', 'phone_one', 'phone_two', 'address', 'city', 'state',
                    'zip', 'country_code', 'timezone', 'bio', 'visibility', 'created_at', 
                    'updated_at'
                ])->flip())->count()) return abort(422, 'Invalid File');

        return true;
    }

    private function save_user($user)
    {
        $u = User::firstOrNew(['email' => $user['email']]);
        
        if ( ! $u->id) $u->create($user);
    }

    private function match_columns($user)
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
            'updated_at' => $user->updated_at
        ];
    }

}