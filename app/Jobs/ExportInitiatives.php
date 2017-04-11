<?php

namespace App\Jobs;

use App\Models\v1\ProjectInitiative;

class ExportInitiatives extends Exporter
{
    public function data(array $request)
    {
        return ProjectInitiative::filter($request)
//            ->with('user')
            ->get();
    }

    public function columns($initiative)
    {
        return [
            'id'             => $initiative->id,
            'type'           => $initiative->type,
            'country'        => country($initiative->country_code),
            'country_code'   => $initiative->country_code,
            'short_desc'     => $initiative->short_desc,'started_at'     => $initiative->started_at->toDateTimeString(),
            'ended_at'       => $initiative->ended_at->toDateTimeString(),
            'created_at'     => $initiative->created_at->toDateTimeString(),
            'updated_at'     => $initiative->updated_at->toDateTimeString(),
        ];
    }
}
