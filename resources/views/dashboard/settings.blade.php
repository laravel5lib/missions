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
        <div class="row hidden-xs">
            <div class="col-sm-8">
                <h3>Settings</h3>
            </div>
            <div class="col-sm-4 text-right">
                <hr class="divider inv sm">
                <action-trigger text="Save Settings" event="save-settings"></action-trigger>
            </div>
        </div>
        <div class="row visible-xs">
            <div class="col-xs-12 text-center">
                <h3>Settings</h3>
            </div>
            <div class="col-xs-12 text-center">
                <action-trigger text="Save Settings" event="save-settings"></action-trigger>
                <hr class="divider inv sm">
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <user-settings id="{{ auth()->user()->id }}"></user-settings>
            </div>
        </div>
    </div>
@endsection