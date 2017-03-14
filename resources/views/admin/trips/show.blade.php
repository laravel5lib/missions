@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h3 class="text-capitalize">
                    <a href="#">
                        {{-- <img class="img-circle av-left img-sm" src="{{ image($trip->campaign->avatar->source . '?w=100') }}" alt="{{ $trip->campaign->name }}"> --}}
                    </a>
                    {{ $trip->campaign->name }} <small>&middot; Trip Details</small>
                </h3>
            </div>
            <div class="col-sm-4 text-right">
                <hr class="divider inv sm">
                <hr class="divider inv">
                <div class="btn-group">
                    <a href="{{ url('admin/campaigns/' . $trip->campaign->id) }}" class="btn btn-primary-darker"><span class="fa fa-chevron-left icon-left"></span></a>
                    <div class="btn-group">
                        <a type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Manage <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ chop(Request::url(), '/' . $tab) }}/edit">Edit</a></li>
                            <li><a data-toggle="modal" data-target="#addReservationModal" data-backdrop="static">Create Reservation</a></li>
                            <li><a data-toggle="modal" data-target="#duplicationModal">Duplicate</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a data-toggle="modal" data-target="#deleteConfirmationModal">Delete</a></li>
                        </ul>
                    </div><!-- end btn-group -->
                </div>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-3">
                @include('admin.trips.tabs.nav')
            </div>
            <div class="col-xs-12 col-sm-8 col-md-9">
                @include('admin.trips.tabs.'.$tab)
            </div>
        </div>

        <admin-trip-duplicate trip-id="{{ $trip->id }}"></admin-trip-duplicate>
        <admin-delete-modal id="{{ $trip->id }}" resource="trip" label="Delete trip?"></admin-delete-modal>
        <div class="modal fade" id="addReservationModal" tabindex="-1" role="dialog" aria-labelledby="addReservationModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Create Reservation</h4>
                    </div>
                    <div class="modal-body">
                        <admin-reservation-create trip-id="{{ $trip->id }}"></admin-reservation-create>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <style>
        .panel dd {
            text-transform: capitalize;
        }
    </style>
@endsection