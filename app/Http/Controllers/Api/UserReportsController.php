<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\v1\User;
use App\Models\v1\Report;
use Illuminate\Http\Request;
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

    public function index($userId, Request $request)
    {
        $reports = $this->user
             ->findOrFail($userId)
             ->reports()
             ->filter($request->all())
             ->orderBy('created_at', 'desc')
             ->paginate($request->get('per_page', 10));

        return $this->response->paginator($reports, new ReportTransformer);
    }

    public function destroy($id)
    {
        $report = $this->report->findOrFail($id);

        if ( ! File::exists($report->source)) 
            return abort(500, 'Unable to delete the report.');

        if (File::delete($report->source)) {
            $report->delete();
            
            return $this->response->noContent();
        }

        return abort(500, 'Unable to delete the report.');
    }
}
