@extends('admin.layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <campaign-trip-edit-wizard trip-id="{{ $tripId }}"></campaign-trip-edit-wizard>
            </div>
        </div>
    </div>
@endsection