@extends('layouts.admin')

@section('header')
    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/campaigns' => 'Campaigns',
        '/admin/campaigns/'.$trip->campaign->id => $trip->campaign->name.' - '.country($trip->country_code),
        '/admin/campaigns/'.$trip->campaign->id.'/groups' => 'Groups',
        'active' => $trip->group->name.' - '.ucfirst($trip->type).' Trip'
    ]])
    @endbreadcrumbs
@endsection

@section('content')
    <hr class="divider inv lg">
    <div class="container-fluid">
        <div class="row">          
            <div class="col-xs-12 col-md-2">
                @sidenav(['links' => $pageLinks])
                @endsidenav
            </div>
            
            <div class="col-xs-12 col-md-7">
                @include('admin.trips.tabs.'.($tab === 'reservations' ? 'details' : $tab))
            </div>

            <div class="col-md-3 small">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#notes" aria-controls="notes" role="tab" data-toggle="tab">Notes</a>
                    </li>
                    <li role="presentation">
                        <a href="#tasks" aria-controls="tasks" role="tab" data-toggle="tab">Tasks</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="notes">
                        <notes type="trips"
                            id="{{ $trip->id }}"
                            user_id="{{ auth()->user()->id }}"
                            :per_page="10">
                        </notes>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tasks">
                        <todos type="trips"
                            id="{{ $trip->id }}"
                            user_id="{{ auth()->user()->id }}">
                        </todos>
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