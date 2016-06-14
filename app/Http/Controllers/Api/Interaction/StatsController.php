<?php

namespace App\Http\Controllers\v1\interaction;

use App\Http\Controllers\v1\Controller;
use App\Models\v1\Interaction\Exam;
use App\Models\v1\Interaction\Site;
//use App\models\v1\interaction\Stat;
use App\Stat;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;


class StatsController extends Controller
{

    /**
     * StatsController constructor.
     * @param Stat $stat
     */
    public function __construct(Stat $stat)
    {
        $this->stat = $stat;
    }

    public function index()
    {
        return $this->stat->save('regions');
    }

//    public function index()
//    {
//
//        $sites = Site::select('region_id', 'created_at', 'updated_at', 'total_decisions', 'total_reached', 'site_type')->whereBetween('created_at', [Carbon::yesterday()->startOfDay(), Carbon::yesterday()->endOfDay()])->get()->toArray();
//
//
//        $exams = Exam::select('region_id', 'created_at', 'updated_at', 'decision as medical_decision', 'party_size', 'treatments')->whereBetween('created_at', [Carbon::yesterday()->startOfDay(), Carbon::yesterday()->endOfDay()])->get()->toArray();
//
//        $data = collect($sites + $exams);
//
//        $results = $data->groupBy('region_id')->map(function ($item, $key) {
//            return $array = [
//                'id' => Uuid::uuid4(),
//                'team_id' => $key,
//                'totals' => json_encode([
//                    'reached' => $item->sum('total_reached') + $item->sum('party_size'),
//                    'decisions' => $item->sum('total_decisions') + $item->where('decision', 1)->count(),
//                    'total_sites' => $item->sum(function ($i) {
//                        return isset($i['site_type']) ? 1 : 0;
//                    }),
//                    'plazas' => $item->where('site_type', 'plaza')->count(),
//                    'churches' => $item->where('site_type', 'church')->count(),
//                    'schools' => $item->where('site_type', 'school')->count(),
//                    'parks' => $item->where('site_type', 'park')->count(),
//                    'stadiums' => $item->where('site_type', 'stadium')->count(),
//                    'markets' => $item->where('site_type', 'market')->count(),
//                    'other' => $item->where('site_type', 'other')->count(),
//                    'treated' => $item->sum('party_size'),
//                    'medical_decisions' => $item->where('decision', 1)->count(),
//                    'medical' => $item->pluck('treatments')->filter(function ($item, $key) {
//                        if( ! $item) return 0;
//
//                        return array_where($item, function ($key, $value) {
//                            return $value == 'medical';
//                        });
//                    })->count(),
//                    'dental' => $item->pluck('treatments')->filter(function ($item, $key) {
//                        if( ! $item) return 0;
//
//                        return array_where($item, function ($key, $value) {
//                            return $value == 'dental';
//                        });
//                    })->count(),
//                    'medication' => $item->pluck('treatments')->filter(function ($item, $key) {
//                        if( ! $item) return 0;
//
//                        return array_where($item, function ($key, $value) {
//                            return $value == 'medication';
//                        });
//                    })->count(),
//                    'eyes' => $item->pluck('treatments')->filter(function ($item, $key) {
//                        if( ! $item) return 0;
//
//                        return array_where($item, function ($key, $value) {
//                            return $value == 'eye glasses';
//                        });
//                    })->count(),
//                    'other_treatments' => $item->pluck('treatments')->filter(function ($item, $key) {
//                        if( ! $item) return 0;
//
//                        return array_where($item, function ($key, $value) {
//                            return $value == 'other';
//                        });
//                    })->count()
//                ]),
//                'created_at' => Carbon::yesterday(),
//                'updated_at' => Carbon::now()
//            ];
//        });
//
//        $this->stat->insert($results->toArray());
//
//        return 'stats updated!';
//    }



}
