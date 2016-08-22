@extends('dashboard.layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="text-muted text-center">My Groups</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <groups-list user-id="{{ auth()->check() ? auth()->id() : null }}" :select-ui="true"></groups-list>
            </div>
        </div>
    </div>

@endsection