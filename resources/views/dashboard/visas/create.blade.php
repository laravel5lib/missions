@extends('dashboard.layouts.default')

@section('styles')
    <link rel="stylesheet" href="http://jcrop-cdn.tapmodo.com/v2.0.0-RC1/css/Jcrop.css" type="text/css">
@endsection
@section('scripts')
    <script src="http://jcrop-cdn.tapmodo.com/v2.0.0-RC1/js/Jcrop.js"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="text-muted text-center">My Visas <small>Create</small></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <visa-create-update></visa-create-update>
            </div>
            <div class="col-sm-4">
                <img src="/images/passport-placeholder.png" alt="" style="width: 100%;height: auto;">
            </div>
        </div>
    </div>
@endsection