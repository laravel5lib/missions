@extends('dashboard.layouts.default')

@section('styles')
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/2.0.4/css/Jcrop.css" type="text/css"> --}}
@endsection
@section('scripts')
    <script src="js/slim.js"></script>
@endsection

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h3 class="hidden-xs">My Media Credentials <small>&middot; Edit</small></h3>
                <h3 class="text-center visible-xs">My Media Credentials<br><small>Edit</small></h3>
            </div>
            <div class="col-sm-4 text-right hidden-xs">
                <hr class="divider inv sm">
                <a onclick="window.history.back()" class="btn btn-primary"><i class="fa fa-chevron-left icon-left"></i> Back</a>
            </div>
            <div class="col-sm-4 text-center visible-xs">
                <a onclick="window.history.back()" class="btn btn-primary"><i class="fa fa-chevron-left icon-left"></i> Back</a>
                <hr class="divider inv sm">
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
<div class="container">
    <div class="row">
            <div class="col-xs-12">
                <media-credential-create-update :is-update="true" id="{{ $id }}"></media-credential-create-update>
            </div>
    </div>
</div>
<hr class="divider inv xlg">
@endsection