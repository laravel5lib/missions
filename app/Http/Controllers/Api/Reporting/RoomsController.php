<?php

namespace App\Http\Controllers\Api\Reporting;

use App\Jobs\Reports\Rooms\RoomsByAccommodationReport;
use App\Jobs\Reports\Rooms\RoomsByPlanReport;
use App\Models\v1\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoomsController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function store($type, Request $request)
    {
        $user = $this->user->findOrFail($request->get('author_id'));

        $report = $this->getReport($type);

        $this->dispatch(new $report($request->all(), $user));

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly.'
        ]);
    }

    private function getReport($type)
    {
        $reports = [
            'plans' => RoomsByPlanReport::class,
            'accommodations' => RoomsByAccommodationReport::class
        ];

        return $reports[$type];
    }
}
