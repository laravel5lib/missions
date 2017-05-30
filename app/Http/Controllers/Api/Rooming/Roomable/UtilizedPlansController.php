<?php

namespace App\Http\Controllers\Api\Rooming\Roomable;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Rooming\ManagePlans;

class UtilizedPlansController extends Controller
{
    public function store($roomableType, $roomableId, Request $request)
    {
        $manager = new ManagePlans($roomableType, $roomableId);
        $manager->use($request->get('plan_ids'));

        return $this->response->created();
    }

    public function destroy($roomableType, $roomableId, $id)
    {
        $manager = new ManagePlans($roomableType, $roomableId);
        $manager->stop($id);

        return $this->response->noContent();
    }
}
