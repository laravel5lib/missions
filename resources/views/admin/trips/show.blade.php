@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3 class="text-capitalize">
                        {{ $trip->group->name }}'s {{ $trip->type }} Trip <br />
                        <small>
                            <i class="fa fa-map-marker"></i> {{ country($trip->country_code) }} &middot;
                            <i class="fa fa-globe"></i> {{ $trip->campaign->name }}
                        </small>
                    </h3>
                </div>
                <div class="col-sm-4 text-right">
                    <hr class="divider inv">
                    <div class="btn-group">
                        <a href="{{ url('/admin/campaigns/'.$trip->campaign->id.'/trips') }}" class="btn btn-link"><span class="fa fa-chevron-left icon-left"></span></a>
                        <div class="btn-group">
                            <a type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Manage <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                @can('update', $trip)
                                <li>
                                    <a href="{{ url('/admin/trips/' . $trip->id . '/edit')}}">Edit</a>
                                </li>
                                @endcan
                                @can('create', \App\Models\v1\Reservation::class)
                                <li>
                                    <a data-toggle="modal"
                                       data-target="#addReservationModal"
                                       data-backdrop="static">
                                        Create Reservation
                                    </a>
                                </li>
                                @endcan
                                @can('create', $trip)
                                <li>
                                    <a data-toggle="modal" data-target="#duplicationModal">Duplicate</a>
                                </li>
                                @endcan
                                @can('delete', $trip)
                                <li role="separator" class="divider"></li>
                                <li>
                                    <a data-toggle="modal" data-target="#deleteConfirmationModal">Delete</a>
                                </li>
                                @endcan
                            </ul>
                        </div><!-- end btn-group -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="details">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-3">
                        @include('admin.trips.tabs.nav')
                    </div>
                    <div class="col-xs-12 col-sm-8 col-md-9">
                        @include('admin.trips.tabs.'.($tab === 'reservations' ? 'details' : $tab))
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="reservations">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>Reservations</h5>
                    </div>
                    <div class="panel-body">
                        <admin-trip-reservations trip-id="{{ $trip->id }}"></admin-trip-reservations>
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

@push('styles')
    <style>
        .panel dd {
            text-transform: capitalize;
        }
    </style>
@endpush

@section('scripts')
    <script type="text/javascript">
        // Javascript to enable link to tab
        var url = document.location.toString();
        var tab = '{{ $tab }}';
        if (url.match('reservations')) {
            $('.nav-tabs a[href="#reservations"]').tab('show');
        }

        // Change history for page-reload
        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            var newHistory = e.currentTarget.href.match('#reservations')
                ? e.currentTarget.href.replace(tab, 'reservations').split('#')[0]
                : e.currentTarget.href.replace('reservations', tab === 'reservations' ? 'details' : tab).split('#')[0]
            history.pushState('data', '', newHistory);
        })
    </script>
@endsection