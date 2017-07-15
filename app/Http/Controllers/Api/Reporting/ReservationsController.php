<?php

namespace App\Http\Controllers\Api\Reporting;

use App\Jobs\Reports\Reservations\ItineraryReport;
use App\Jobs\Reports\Reservations\RoomingReport;
use App\Models\v1\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Reports\Reservations\BasicReport;
use App\Jobs\Reports\Reservations\FundsReport;
use App\Jobs\Reports\Reservations\TravelReport;
use App\Jobs\Reports\Reservations\RequirementsReport;

class ReservationsController extends Controller
{
    protected $user;
    
    function __construct(User $user)
    {
        $this->user = $user;
    }

    public function store(Request $request, $type)
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
            'basic' => BasicReport::class,
            'funds' => FundsReport::class,
            'requirements' => RequirementsReport::class,
            'travel' => TravelReport::class,
            'rooming' => RoomingReport::class,
            'itinerary' => ItineraryReport::class
        ];

        return $reports[$type];
    }
}
