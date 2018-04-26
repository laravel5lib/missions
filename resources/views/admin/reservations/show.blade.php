@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <ul class="breadcrumb">
                    <li><a href="{{ url('/admin') }}">Dashboard</a></li>
                    <li><a href="{{ url('/admin/reservations') }}">Reservations</a></li>
                    <li class="active">{{ $reservation->name }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@if($reservation->deleted_at)
<div class="darker-bg-primary">
    <div class="container-fluid">
        <div class="col-sm-8 text-center">
            <hr class="divider inv sm">
            <h5>This reservation was dropped and is no longer active.</h5>
            <hr class="divider inv sm">
        </div>
        <div class="col-sm-4 text-center">
            <hr class="divider inv sm hidden-xs">
            <button data-toggle="modal" data-target="#restoreConfirmationModal" class="btn btn-sm btn-white-hollow"><i class="fa fa-undo"></i> Restore</button>
            <hr class="divider inv sm">
        </div>
    </div><!-- end container -->
</div><!-- end dark-bg-primary -->
@endif
<hr class="divider inv lg">
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-md-2">
            @include('admin.reservations.layouts.menu', [
                'links' => config('navigation.admin.reservation'),
                'reservation' => $reservation,
                'rep' => $reservation->rep ? $reservation->rep : $reservation->trip->rep
                ])
        </div>
        <div class="col-xs-12 col-md-7">
            @yield('tab')
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
                    <notes type="reservations"
                        id="{{ $reservation->id }}"
                        user_id="{{ auth()->user()->id }}"
                        :per_page="10"
                        :can-modify="{{ auth()->user()->can('modify-notes')?1:0 }}">
                    </notes>
                </div>
                <div role="tabpanel" class="tab-pane" id="tasks">
                    <todos type="reservations"
                        id="{{ $reservation->id }}"
                        user_id="{{ auth()->user()->id }}"
                        :can-modify="{{ auth()->user()->can('modify-todos')?1:0 }}">
                    </todos>
                </div>
            </div>

        </div>
    </div>
</div>

<admin-delete-modal
    id="{{ $reservation->id }}"
    resource="reservation"
    label="Drop reservation?"
    action="Drop">
</admin-delete-modal>
<restore-reservation id="{{ $reservation->id }}"></restore-reservation>
<transfer-reservation id="{{ $reservation->id }}"
                      campaign-id="{{ $reservation->trip->campaign_id }}">
</transfer-reservation>
@endsection