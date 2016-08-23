@extends('dashboard.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>My Groups</h3>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <groups-list user-id="{{ auth()->check() ? auth()->id() : null }}" :select-ui="true"></groups-list>
            </div>
        </div>
    </div>
<hr class="divider inv lg">
@endsection