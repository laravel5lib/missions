<?php

namespace App\Jobs;

use App\Models\v1\Visa;

class ExportVisas extends Exporter
{
    public function data($request)
    {
        return Visa::filter($request)
                ->with('user')
                ->get();
    }

    public function columns($visa)
    {
        return [
            'number' => $visa->number,
            'given_names' => $visa->given_names,
            'surname' => $visa->surname,
            'country' => country($visa->country_code),
            'country_code' => $visa->country_code,
            'issued_at' => $visa->issued_at->toDateTimeString(),
            'expires_at' => $visa->expires_at->toDateTimeString(),
            'user_name' => $visa->user->name,
            'user_email' => $visa->user->email,
            'user_phone_one' => $visa->user->phone_one,
            'user_phone_two' => $visa->user->phone_two,
            'created_at' => $visa->created_at->toDateTimeString(),
            'updated_at' => $visa->updated_at->toDateTimeString()
        ];
    }
}
