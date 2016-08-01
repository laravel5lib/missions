@extends('admin.layouts.default')
@section('scripts')
    <script src="http://jcrop-cdn.tapmodo.com/v2.0.0-RC1/js/Jcrop.js"></script>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>Upload <small>Create</small></h3>
                <hr>
                <upload-create></upload-create>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="http://jcrop-cdn.tapmodo.com/v2.0.0-RC1/css/Jcrop.css" type="text/css">
@endsection