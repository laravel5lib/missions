@extends('admin.layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>Trips <small>Create</small></h3>
                <hr>
                <campaign-trip-create-wizard campaign-id="{{ $campaign->id }}" countries="{{ $campaign->countries }}"></campaign-trip-create-wizard>
            </div>
        </div>
    </div>
@endsection