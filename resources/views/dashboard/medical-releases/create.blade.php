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
            <div class="col-sm-12">
                <h3>My Medical Releases <small>&middot; Create</small></h3>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
<div class="container">
        <div class="row">
            <div class="col-sm-8">
                <medical-create-update></medical-create-update>
            </div>
            <div class="col-sm-4">
                <img src="/images/passport-placeholder.png" alt="" style="width: 100%;height: auto;">
            </div>
        </div>
</div>
@endsection