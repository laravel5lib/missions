@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3 class="text-capitalize">
                        {{ $interest->trip->campaign->name }} <small>&middot; Trip Interest</small>
                    </h3>
                </div>
                <div class="col-sm-4 text-right">
                    <hr class="divider inv">
                    <a href="{{ url('admin/reservations/prospects') }}" class="btn btn-default">
                        <i class="fa fa-chevron-left"></i> All Interests
                    </a>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <trip-interest-editor id="{{ $interest->id }}"></trip-interest-editor>
            </div>
        </div>
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <todos type="trip_interests"
                       id="{{ $interest->id }}"
                       user_id="{{ auth()->user()->id }}"
                       :can-modify="{{ auth()->user()->can('modify-todos')?1:0 }}">
                </todos>
            </div>
        </div>
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <notes type="trip_interests"
                       id="{{ $interest->id }}"
                       user_id="{{ auth()->user()->id }}"
                       :per_page="3"
                       :can-modify="{{ auth()->user()->can('modify-notes')?1:0 }}">
                </notes>
            </div>
        </div>
    </div>
@endsection