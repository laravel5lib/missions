@extends('admin.layouts.default')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/2.0.4/css/Jcrop.css" type="text/css">
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/2.0.4/js/Jcrop.min.js"></script>
@endsection

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row hidden-xs">
            <div class="col-sm-8">
                <h3>Uploads <small>&middot; Edit</small></h3>
            </div>
            <div class="col-sm-4 text-right">
                <hr class="divider inv sm">
                <a class="btn btn-default" onclick="window.history.back()"><i class="fa fa-chevron-left icon-left"></i> Back</a>
            </div>
        </div>
        <div class="row visible-xs">
            <div class="col-sm-8 text-center">
                <h3>Uploads <small>&middot; Edit</small></h3>
            </div>
            <div class="col-sm-4 text-center">
                <a class="btn btn-default" onclick="window.history.back()"><i class="fa fa-chevron-left icon-left"></i> Back</a>
                <hr class="divider inv sm">
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <upload-create-update :is-update="true" :lock-type="true" upload-id="{{ $id }}"></upload-create-update>
                    </div><!-- end panel-body -->
                </div><!-- end panel -->
            </div>
        </div>
    </div>
@endsection