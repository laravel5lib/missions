<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\UploadRequest;
use App\Http\Transformers\v1\UploadTransformer;
use App\Models\v1\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use League\Glide\Server;

class UploadsController extends Controller
{

    /**
     * @var Upload
     */
    private $upload;
    /**
     * @var Server
     */
    private $server;

    /**
     * UploadsController constructor.
     * @param Upload $upload
     * @param Server $server
     */
    public function __construct(Upload $upload, Server $server)
    {
        $this->upload = $upload;
        $this->server = $server;
    }

    /**
     * Show all uploads.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $uploads = $this->upload
                        ->filter($request->all())
                        ->paginate($request->get('per_page', 10));

        return $this->response->paginator($uploads, new UploadTransformer);
    }

    /**
     * Create and upload a new upload.
     *
     * @param UploadRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(UploadRequest $request)
    {
        if ($request->get('type') == 'video') {
            $upload = $this->saveVideo($request);

            return $this->response->item($upload, new UploadTransformer);
        }

        if ($request->get('type') == 'file') {
            $upload = $this->savePDF($request);

            return $this->response->item($upload, new UploadTransformer);
        }

        $stream = $this->process($request);

        $source = $this->upload($stream, $request->only('path', 'name', 'file'));

        $upload = $this->save($source, $request);

        return $this->response->item($upload, new UploadTransformer);
    }

    /**
     * Show the specified upload.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $upload = $this->upload->findOrFail($id);

        return $this->response->item($upload, new UploadTransformer);
    }

    /**
     * Display an image
     *
     * @param $path
     * @param Request $request
     * @return mixed
     * @internal param $source
     */
    public function display($path, Request $request)
    {
        $this->server->outputImage($path, $request->all());
    }

    /**
     * Display a file
     *
     * @param  string $path
     * @return response
     */
    public function display_file($path)
    {
        return response()->make(Storage::disk('s3')->get($path), 200, [
                'Content-Type' => 'application/pdf'
            ]);
    }

    /**
     * Update the specified upload.
     *
     * @param UploadRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(UploadRequest $request, $id)
    {
        $upload = $this->upload->findOrFail($id);

        if ($request->has('file')) {
            $stream = $this->process($request);

            $source = $this->upload($stream, $request->only('path', 'name', 'file'));

            if ($source) {
                // delete old file
                Storage::disk('s3')->delete($upload->source);
                // clear cache
                $this->server->deleteCache($upload->source);
            }
        }

        $upload->update([
            'source' => isset($source['source']) ? $source['source'] : $upload->source,
            'name' => $request->get('name'),
            'type' => $request->get('type')
        ]);

        if ($request->has('tags')) {
            $upload->retag($request->get('tags'));
        }

        return $this->response->item($upload, new UploadTransformer);
    }

    /**
     * Delete the specified upload.
     *
     * @param $id
     */
    public function destroy($id)
    {
        $upload = $this->upload->findOrFail($id);

        Storage::disk('s3')->delete($upload->source);

        $this->server->deleteCache($upload->source);

        $upload->delete();

        $this->response->noContent();
    }

    /**
     * Process the image.
     *
     * @param $request
     * @return mixed
     */
    private function process($request)
    {
        // resize or crop and stream image
        $img = Image::make($request->get('file'));

        if ($request->has('x_axis') and $request->has('y_axis')) {
            $img->crop(
                $request->get('width'),
                $request->get('height'),
                $request->get('x_axis'),
                $request->get('y_axis')
            );
        } elseif ($request->get('height') and $request->get('width')) {
            $img->resize($request->get('width'), $request->get('height'));
        }

        $img->encode($request->get('extension', 'jpg'), '100');

        $stream = $img->stream();

        return $stream;
    }

    /**
     * Upload the image to AWS.
     *
     * @param $stream
     * @param $request
     * @return array
     */
    private function upload($stream, $request)
    {
        $path = $request['path'];
        $filename = isset($request['name']) ? snake_case(trim($request['name'])).'_'.time() : time();
        $source = $path.'/'.$filename.'.jpg';

        // store image on s3 server
        Storage::disk('s3')->put(
            $source,
            $stream->__toString()
        );

        return ['source' => $source, 'filename' => $filename];
    }

    /**
     * Save the upload record in storage.
     *
     * @param $source
     * @param Request $request
     * @return Upload
     * @internal param $type
     */
    private function save($source, Request $request)
    {
        // create new upload record
        $new = $this->upload->create([
            'name' => $request->get('name'),
            'type' => $request->get('type'),
            'source' => $source['source'],
            'meta' => [
                'width' => $request->get('width'),
                'height' => $request->get('height'),
                'size' => null
            ]
        ]);

        if ($request->has('tags')) {
            $new->tag($request->get('tags'));
        }

        return $new;
    }

    /**
     * Get video format from source url.
     *
     * @param $url
     * @return string
     */
    private function getVideoFormat($url)
    {
        if (str_contains($url, 'youtube')) {
            return 'youtube';
        }

        if (str_contains($url, 'vimeo')) {
            return 'vimeo';
        }

        return 'other';
    }

    /**
     * Save PDF.
     *
     * @param $request
     * @return Upload
     */
    private function savePDF($request)
    {
        $file = $request->get('file');
        $path = $request['path'];
        $filename = isset($request['name']) ? snake_case(trim($request['name'])).'_'.time() : time();
        $source = $path.'/'.$filename.'.pdf';

        Storage::disk('s3')
            ->put($source, fopen($file, 'r+'));

        return $this->upload->create([
            'name' => $request->get('name'),
            'type' => 'file',
            'source' => $source,
            'meta' => [
                'format' => 'pdf'
            ]
        ]);
    }
    /**
     * Save Video.
     *
     * @param $request
     * @return Upload
     */
    private function saveVideo($request)
    {
        return $this->upload->create([
            'name' => $request->get('name'),
            'type' => 'video',
            'source' => $request->get('url'),
            'meta' => [
                'format' => $this->getVideoFormat($request->get('url'))
            ]
        ]);
    }
}
