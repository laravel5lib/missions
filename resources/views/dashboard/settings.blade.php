@extends('dashboard.layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="text-muted text-center">My Settings</h1>
            </div>
            <div class="col-sm-12">
                <user-settings></user-settings>
            </div>
        </div>
    </div>
@endsection