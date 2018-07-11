<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\v1\CampaignGroup;
use App\Http\Controllers\Controller;
use App\Http\Resources\RequirementResource;

class CampaignGroupRequirementController extends Controller
{
    public function index($groupId)
    {
        $group = CampaignGroup::whereUuid($groupId)->firstOrFail();

        $requirements = $group->requireables()->paginate(request()->input('per_page', 25));

        return RequirementResource::collection($requirements);
    }
}
