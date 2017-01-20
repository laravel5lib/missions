<?php

namespace App\Jobs;

use App\Models\v1\Passport;

class ExportPassports extends Exporter
{
    public function data($request)
    {
        return Passport::filter($request)
                ->with('user')
                ->get();
    }

    public function columns($passport)
    {
        return [
            'number' => $passport->number,
            'given_names' => $passport->given_names,
            'surname' => $passport->surname,
            'birth_country' => country($passport->birth_country),
            'citizenship' => country($passport->citizenship),
            'issued_at' => $passport->issued_at->toDateTimeString(),
            'expires_at' => $passport->expires_at->toDateTimeString(),
            'user_name' => $passport->user->name,
            'user_email' => $passport->user->email,
            'user_phone_one' => $passport->user->phone_one,
            'user_phone_two' => $passport->user->phone_two,
            'created_at' => $passport->created_at->toDateTimeString(),
            'updated_at' => $passport->updated_at->toDateTimeString()
        ];
    }
}
