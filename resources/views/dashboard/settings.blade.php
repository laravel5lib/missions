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
            <div class="col-sm-12" v-tour-guide="">
                <user-settings></user-settings>
            </div>
        </div>
    </div>
@endsection

@section('tour')
    <script>
        window.pageSteps = [
            {
                id: 'avatar',
                title: 'Profile Avatar',
                text: 'Upload a picture of yourself. Show us your humanity!',
                attachTo: {
                    element: '.tour-step-avatar',
                    on: 'top'
                },
            } ,
            {
                id: 'cover',
                title: 'Profile Cover',
                text: 'Like they say, "A picture says a thousand words..." So, you don\'t have to :p',
                attachTo: {
                    element: '.tour-step-cover',
                    on: 'top'
                }
            },
        ];
    </script>
@endsection