<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Campaign;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use App\Http\Controllers\Controller;

class CampaignMediaController extends Controller
{
    public function store(Campaign $campaign, Request $request)
    {
        $img = Image::make($request->get('file'));
        $img->encode('jpg', '100');

        $media = $campaign->clearMediaCollection('avatar')
                          ->addMediaFromBase64(base64_encode($img))
                          ->toMediaCollection('avatar');
        
        return response()->json([
            'url' => $media->getUrl('optimized'),
            'id' => $media->id
        ]);
    }
}
