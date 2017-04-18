@extends('dashboard.layouts.default')

@section('styles')
    <link rel="stylesheet" href="/css/slim.css" type="text/css">
@endsection
@section('scripts')
    <script src="/js/slim.js"></script>
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
                {{-- <action-trigger text="Save Settings" event="save-settings"></action-trigger> --}}
                <a id="settings-profile-link" class="btn btn-primary" href="{{ url(auth()->user()->slug->url) }}">
                    My Profile
                </a>
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
                text: 'Upload a picture of yourself. Show us your smile!',
                attachTo: {
                    element: '.tour-step-avatar',
                    on: 'top'
                },
            } ,
            {
                id: 'cover',
                title: 'Profile Cover',
                text: 'Choose a cover photo. Like they say, "A picture says a thousand words."',
                attachTo: {
                    element: '.tour-step-cover',
                    on: 'top'
                }
            },
            {
                id: 'privacy',
                title: 'Privacy',
                text: 'Control your privacy by setting your profile to <em>public</em> or <em>private</em>. Only you can see a private profile.',
                attachTo: {
                    element: '.tour-step-privacy',
                    on: 'top'
                }
            },
            {
                id: 'url',
                title: 'Custom URL',
                text: 'Customize your profile web address. A unique one is provided.',
                attachTo: {
                    element: '.tour-step-url',
                    on: 'top'
                }
            },
            {
                id: 'contact',
                title: 'Contact Information',
                text: 'Save time when registering for a trip or making a donation by providing this information here.',
                attachTo: {
                    element: '.tour-step-contact',
                    on: 'top'
                }
            },
            {
                id: 'social',
                title: 'Get Social',
                text: 'Add links to your social media accounts and share them on your profile page. Simply copy and paste the urls to your social media profiles.',
                attachTo: {
                    element: '.tour-step-social',
                    on: 'top'
                }
            },
            {
                id: 'save',
                title: 'Save Settings',
                text: 'Don\'t forget to save your settings!',
                attachTo: {
                    element: '.tour-step-save',
                    on: 'top'
                }
            },
        ];
    </script>
@endsection