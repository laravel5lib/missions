@extends('dashboard.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h3>Settings</h3>
            </div>
            <div class="col-sm-4">
                <hr class="divider inv sm">
                <action-trigger text="Save Settings" event="save-settings"></action-trigger>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <user-settings></user-settings>
            </div>
        </div>
    </div>
@endsection