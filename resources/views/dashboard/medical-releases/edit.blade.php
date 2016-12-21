@extends('dashboard.layouts.default')

@section('styles')
    <link rel="stylesheet" href="http://jcrop-cdn.tapmodo.com/v2.0.0-RC1/css/Jcrop.css" type="text/css">
@endsection
@section('scripts')
    <script src="http://jcrop-cdn.tapmodo.com/v2.0.0-RC1/js/Jcrop.js"></script>
@endsection

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h3>My Medical Releases <small>&middot; Edit</small></h3>
            </div>
            <div class="col-sm-4 text-right">
                <hr class="divider inv sm">
                <a href="/dashboard/records/medical-releases" class="btn btn-primary"><i class="fa fa-chevron-left icon-left"></i> Back To All</a>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
<div class="container">
    <div class="row">
            <div class="col-xs-12">
                <medical-create-update :is-update="true" id="{{ $id }}"></medical-create-update>
            </div>
    </div>
</div>
<hr class="divider inv xlg">
@endsection