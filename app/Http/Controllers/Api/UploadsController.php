<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadRequest;
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
        $this->middleware('api.auth', ['except' => ['show']]);
        $this->middleware('jwt.refresh', ['except' => ['show']]);
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
        $stream = $this->process($request->file('file'));

        $source = $this->upload($stream, $request->only('path', 'name', 'file'));

        $upload = $this->save($source, $request->only('name', 'type'));

        $this->response->item($upload, new UploadTransformer);
    }

    private function process($file)
    {
        // resize and stream image
        $img = Image::make($file)->resize(500, 500);
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

        return $source;
    }

    private function save($source, $request)
    {
        // create new upload record
        $upload = new Upload;
        $upload->id = uniqid();
        $upload->source = $source;
        $upload->name = $filename;
        $upload->type = $request->get('type');
        $upload->save();

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
