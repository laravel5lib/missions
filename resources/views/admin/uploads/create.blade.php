@extends('admin.layouts.default')

@section('styles')
    <link rel="stylesheet" href="http://jcrop-cdn.tapmodo.com/v2.0.0-RC1/css/Jcrop.css" type="text/css">
@endsection
@section('scripts')
    <script src="http://jcrop-cdn.tapmodo.com/v2.0.0-RC1/js/Jcrop.js"></script>
@endsection

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row hidden-xs">
            <div class="col-sm-8">
                <h3>Uploads <small>&middot; Create</small></h3>
            </div>
            <div class="col-sm-4 text-right">
                <hr class="divider inv sm">
                <a class="btn btn-default" href="/admin/uploads"><i class="fa fa-chevron-left icon-left"></i> Back To Library</a>
            </div>
        </div>
        <div class="row visible-xs">
            <div class="col-sm-8 text-center">
                <h3>Uploads <small>&middot; Edit</small></h3>
            </div>
            <div class="col-sm-4 text-center">
                <a class="btn btn-default" href="/admin/uploads"><i class="fa fa-chevron-left icon-left"></i> Back To Library</a>
                <hr class="divider inv sm">
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <upload-create-update></upload-create-update>
            </div>
        </div>
    </div>
@endsection