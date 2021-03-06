<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\v1\User;
use App\Models\v1\Report;
use Illuminate\Http\Request;
use App\Exports\GroupsExport;
use App\Exports\SquadsExport;
use App\Exports\FlightsExport;
use App\Exports\RegionsExport;
use App\Exports\InterestsExport;
use App\Exports\PassengersExport;
use App\Exports\ItinerariesExport;
use App\Exports\ReservationsExport;
use App\Exports\SquadMembersExport;
use App\Exports\TransactionsExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Transformers\v1\ReportTransformer;

class UserReportsController extends Controller
{
    private $user;
    private $report;

    public function __construct(User $user, Report $report)
    {
        $this->user = $user;
        $this->report = $report;
    }

    /**
     * Get all user reports
     *
     * @param  string  $userId
     * @param  Request $request
     * @return response
     */
    public function index($userId)
    {
        $reports = $this->user
            ->findOrFail($userId)
            ->reports()
            ->filter(request()->all())
            ->orderBy('created_at', 'desc')
            ->paginate(request()->get('per_page', 10));

        return $this->response->paginator($reports, new ReportTransformer);
    }

    public function create($type, $format, Request $request)
    {
        $filename = snake_case($request->input('filename', $type).'_'.time());

        $location = 'export/reports/'.auth()->user()->id.'/'.$filename.'.'.$format;

        $exportable = [
            'reservations' => ReservationsExport::class,
            'groups' => GroupsExport::class,
            'passengers' => PassengersExport::class,
            'itineraries' => ItinerariesExport::class,
            'flights' => FlightsExport::class,
            'interests' => InterestsExport::class,
            'transactions' => TransactionsExport::class,
            'squad-members' => SquadMembersExport::class,
            'squads' => SquadsExport::class,
            'regions' => RegionsExport::class
        ];

        if ((new $exportable[$type]($request))->store($location, 's3')) {

            $report = Report::create([
                'name' => $filename,
                'type' => $format,
                'source' => $location,
                'user_id' => auth()->user()->id
            ]);

            return response()->json([
                'message' => 'Export created.', 
                'data' => [
                    'id' => $report->id,
                    'name' => $report->name,
                    'type' => $report->type,
                    'source' => $report->source
                ]
            ], 201);
        }

        return response()->json(['message' => 'Unable to create export.'], 422);
    }

    /**
     * Delete the report
     *
     * @param  string $id
     * @return response
     */
    public function destroy($id)
    {
        $report = $this->report->findOrFail($id);

        Storage::disk('s3')->delete($report->source);

        $report->delete();

        return $this->response->noContent();
    }
}
