<?php

namespace App\Jobs;

use App\Models\v1\Essay;

class ExportEssays extends Exporter
{
    public function data($request)
    {
        return Essay::filter($request)
//            ->with('user')
            ->get();
    }

    public function columns($essay)
    {
        return [
            'id' => $essay->id,
            'author_name' => $essay->author_name,
            'subject' => $essay->subject,
            'created_at' => $essay->created_at->toDateTimeString(),
            'updated_at' => $essay->updated_at->toDateTimeString(),
        ];
    }
}
