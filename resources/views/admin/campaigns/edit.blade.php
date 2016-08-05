@extends('admin.layouts.default')

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
                <campaign-edit campaign-id="{{ $campaignId }}"></campaign-edit>
            </div>
        </div>
    </div>
@endsection