<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\UploadRequest;
use App\Http\Transformers\v1\UploadTransformer;
use App\Models\v1\Upload;
use Illuminate\Http\Request;

use App\Http\Requests;
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

    public function index(Request $request)
    {
        $uploads = $this->upload
                        ->filter($request->all())
                        ->paginate($request->get('per_page', 10));

        return $this->response->paginator($uploads, new UploadTransformer);
    }

    public function store(UploadRequest $request)
    {
        $stream = $this->process($request);

        $source = $this->upload($stream, $request->only('path', 'name', 'file'));

        $upload = $this->save($source, $request->get('type'));

        $this->response->item($upload, new UploadTransformer);
    }

    private function process($request)
    {
        // resize or crop and stream image
        $img = Image::make($request->file('file'));

        if($request->has('x_axis') and $request->has('y_axis')) {
            $img->crop(
                $request->get('width'), $request->get('height'),
                $request->get('x_axis'), $request->get('y_axis')
            );
        }
        elseif ($request->has('height') and $request->has('width')) {
            $img->resize($request->get('width'), $request->get('height'));
        }

        $stream = $img->stream();

        return $stream;
    }

    private function upload($stream, $request)
    {
        $path = $request['path'];
        $filename = isset($request['name']) ? $request['name'] : time();
        $file = $request['file'];
        $source = $path.'/'.$filename.'.'.$file->getClientOriginalExtension();

        // store image on s3 server
        Storage::disk('s3')->put(
            $source,
            $stream->__toString()
        );

        return ['source' => $source, 'filename' => $filename];
    }

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


    public function show($id)
    {
        $upload = $this->upload->findOrFail($id);

        return $this->response->item($upload, new UploadTransformer);
    }

    public function update()
    {
        //
    }

    public function destroy()
    {
        //
    }
}
