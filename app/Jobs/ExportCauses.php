<?php

namespace App\Jobs;

use App\Models\v1\ProjectCause;

class ExportCauses extends Exporter
{
    public function data($request)
    {
        return ProjectCause::get();
    }

    public function columns($cause)
    {
        $country_names = [];
        $country_codes = [];
        foreach ($cause->countries as $country) {
            $country_names[] = country($country);
            $country_codes[] = $country;
        }

        return [
            'id'              => $cause->id,
            'name'            => $cause->name,
            'short_desc'      => $cause->short_desc,
            'countries'       => $country_names,
            'country_codes'   => $country_codes,
            'created_at'      => $cause->created_at->toDateTimeString(),
            'updated_at'      => $cause->updated_at->toDateTimeString(),
        ];
    }
}
