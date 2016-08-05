<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Upload;
use App\Http\Controllers\Controller;

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
    }

    /**
     * Show a list of uploads.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('view', $this->upload);

        return view('admin.uploads.index');
    }

    /**
     * Show the specified upload.
     *
     * @param $id
     * @return $this
     */
    public function show($id)
    {
        $this->authorize('view', $this->upload);

        $user = $this->api->get('uploads/'.$id, ['include' => '']);

        return view('admin.uploads.show')->with('user', $user);
    }

    /**
     * Edit the specified upload.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $this->authorize('edit', $this->upload);

        return view('admin.uploads.edit', compact('id'));
    }

    /**
     * Create a new upload.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', $this->upload);

        return view('admin.uploads.create');
    }
}
