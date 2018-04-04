<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\v1\Fundraiser;
use App\Http\Controllers\Controller;

class FundraiserReminderController extends Controller
{
    public function store($fundraiser_id, Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:60'
        ]);

        $fundraiser = Fundraiser::findOrFail($fundraiser_id);

        $fundraiser->reminders()->create([
            'email' => $request->get('email')
        ]);

        return $this->response->created();
    }
}
