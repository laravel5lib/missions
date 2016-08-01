@extends('admin.layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>Upload <small>Edit</small></h3>
                <hr>
                <upload-edit upload-id="{{ $id }}"></upload-edit>
            </div>
        </div>
    </div>
@endsection