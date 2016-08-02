<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\UploadRequest;
use App\Http\Transformers\v1\UploadTransformer;
use App\Models\v1\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadsController extends Controller
{

    /**
     * @var Upload
     */
    private $upload;

    /**
     * UploadsController constructor.
     * @param Upload $upload
     */
    public function __construct(Upload $upload)
    {
        $this->upload = $upload;
//        $this->middleware('api.auth', ['except' => ['show']]);
//        $this->middleware('jwt.refresh', ['except' => ['show']]);
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
     */
    public function store(UploadRequest $request)
    {
        $stream = $this->process($request);

        $source = $this->upload($stream, $request->only('path', 'name', 'file'));

        $upload = $this->save($source, $request->get('type'));

        $this->response->item($upload, new UploadTransformer);
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
     * Update the specified upload.
     *
     * @param UploadRequest $request
     * @param $id
     */
    public function update(UploadRequest $request, $id)
    {
        $stream = $this->process($request);

        $source = $this->upload($stream, $request->only('path', 'name', 'file'));

        $upload = $this->upload->findOrFail($id);
        $upload->update([
            'source' => $source['source'],
            'name' => $request->get('name'),
            'type' => $request->get('type')
        ]);

        $this->response->item($upload, new UploadTransformer);
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

        if($request->has('x_axis') and $request->has('y_axis')) {
            $img->crop(
                $request->get('width'), $request->get('height'),
                $request->get('x_axis'), $request->get('y_axis')
            );
        }
        elseif ($request->has('height') and $request->has('width')) {
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
     * @param $type
     * @return Upload
     */
    private function save($source, $type)
    {
        // create new upload record
        $upload = new Upload;
        $upload->updateOrCreate([
            'source' => $source['source'],
            'name' => $source['filename'],
            'type' => $type
        ]);

        return $upload;
    }
}
