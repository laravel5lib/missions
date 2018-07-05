<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Jobs\NotifyOfPublishedSquads;

class SquadPublicationController extends Controller
{
    public function update(Request $request)
    {
        $squads = $request->input('squads');
        $status = $request->input('published');

        foreach($squads as $squad)
        {
            DB::table('squads')
                ->where('uuid', $squad)
                ->update(['published' => $status]);
        }

        if ($status) {
            NotifyOfPublishedSquads::dispatch($squads);
        }

        return response()->json(['message' => 'Squads updated.'], 200);
    }
}
