@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3 class="text-capitalize">
                        Trip Interest
                    </h3>
                </div>
                <div class="col-sm-4 text-right">
                    <hr class="divider inv sm">
                    <a href="{{ url('admin/reservations/prospects') }}" class="btn btn-default-hollow">
                        <i class="fa fa-list"></i> All Interests
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
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>Direct Link to Trip Registration Page</h5>
                    </div>
                    <div class="panel-body">
                        <pre style="overflow: scroll">{{ url('/trips/' . $interest->trip_id) }}</pre>
                        <span class="help-block">Copy & Paste this link to share with the interested party.</span>
                    </div>
                </div>
            </div>
        </div>

        @can('view', \App\Models\v1\Todo::class)
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <todos type="trip_interests"
                       id="{{ $interest->id }}"
                       user_id="{{ auth()->user()->id }}">
                </todos>
            </div>
        </div>
        @endcan

        @can('view', \App\Models\v1\Note::class)
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <notes type="trip_interests"
                       id="{{ $interest->id }}"
                       user_id="{{ auth()->user()->id }}"
                       :per_page="3">
                </notes>
            </div>
        </div>
        @endcan
    </div>
@endsection