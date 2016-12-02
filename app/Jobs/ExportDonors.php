<?php

namespace App\Jobs;

class ExportDonors extends Exporter
{
    public function columns($donor)
    {
        return [
            'name' => $donor->name,
            'company' => $donor->company,
            'email' => $donor->email,
            'phone' => $donor->phone,
            'address' => $donor->address,
            'city' => $donor->city,
            'state' => $donor->state,
            'zip' => $donor->state,
            'country' => country($donor->country_code),
            'account_type' => $donor->account_type ? str_singular($donor->account_type) : 'guest',
            'created' => $donor->created_at->toDateTimeString(),
            'updated' => $donor->updated_at->toDateTimeString()
        ];
    }
}
