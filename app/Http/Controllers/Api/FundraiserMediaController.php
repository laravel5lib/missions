<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\v1\Fundraiser;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class FundraiserMediaController extends Controller
{
    public function store($uuid, Request $request)
    {
        $fundraiser = Fundraiser::whereUuid($uuid)->first();

        if (request()->has('featured')) {

            if (request()->has('url')) {
                return $this->attachImage($fundraiser, $request);
            }

            return $this->processFeaturedImage($fundraiser, $request);
        }

        return $this->processImage($fundraiser, $request);
    }

    protected function attachImage($fundraiser, $request)
    {
        $url = url($request->url);

        $media = $fundraiser->clearMediaCollection('featured')
                            ->addMediaFromUrl($url)
                            ->usingFileName('featured_photo.jpg')
                            ->toMediaCollection('featured');

        return response()->json([
            'url' => $media->getUrl('optimized'),
            'id' => $media->id
        ]);
    }

    protected function processFeaturedImage($fundraiser, $request)
    {
        $img = Image::make($request->get('file'));
        $img->encode('jpg', '100');

        $media = $fundraiser->clearMediaCollection('featured')
                            ->addMediaFromBase64(base64_encode($img))
                            ->usingFileName('featured_photo.jpg')
                            ->toMediaCollection('featured');
        
        return response()->json([
            'url' => $media->getUrl('optimized'),
            'id' => $media->id
        ]);
    }

    protected function processImage($fundraiser, $request)
    {
        $filename = md5($fundraiser->id.time());

        $img = Image::make($request->file('file'));

        $img->resize(null, 800, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $stream = $img->stream();

        Storage::disk('s3')->put('fundraisers/'.$fundraiser->uuid.'/photos/'.$filename.'.'.$request->file->extension(), $stream->__toString(), 'public');

        // should add a queue job to optimize this image!

        return response()->json([
            'url' => 'https://'.env('AWS_BUCKET').'.s3.amazonaws.com/fundraisers/'.$fundraiser->uuid.'/photos/'.$filename.'.'.$request->file->extension(),
            'id' => 'test'
        ]);
    }

    public function destroy(Request $request)
    {
        $urls = [];

        foreach($request->urls as $key => $value) {
            $urls[$key] = str_replace('https://'.env('AWS_BUCKET').'.s3.amazonaws.com/', "", $value);
        }
        
        Storage::disk('s3')->delete($urls);
    }
}
