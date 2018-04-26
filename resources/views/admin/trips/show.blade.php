@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <ul class="breadcrumb">
                        <li><a href="{{ url('/admin') }}">Dashboard</a></li>
                        <li><a href="{{ url('/admin/campaigns') }}">Campaigns</a></li>
                        <li>
                            <a href="{{ url('/admin/campaigns/'.$trip->campaign->id.'/trips') }}">
                                {{ $trip->campaign->name }} - {{ country($trip->country_code) }}
                            </a>
                        </li>
                        <li class="active">{{ $trip->group->name }} - {{ ucfirst($trip->type) }} Trip</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container-fluid">
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="details">
                <div class="row">
                    <div class="col-xs-12 col-md-2">
                        @include('admin.trips.tabs.nav')
                    </div>
                    <div class="col-xs-12 col-md-7">
                        @include('admin.trips.tabs.'.($tab === 'reservations' ? 'details' : $tab))
                    </div>
                    <div class="col-md-3 small">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#home" aria-controls="home" role="tab" data-toggle="tab">Notes</a>
                            </li>
                            <li role="presentation">
                                <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Tasks</a>
                            </li>
                            <li role="presentation">
                                <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Activity</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <admin-trip-duplicate trip-id="{{ $trip->id }}"></admin-trip-duplicate>
        <admin-delete-modal id="{{ $trip->id }}"
                            resource="trip"
                            label="Delete trip?"
                            redirect="/admin/campaigns/{{ $trip->campaign->id}}">
        </admin-delete-modal>
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
@endsection