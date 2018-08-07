@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/campaigns' => 'Campaigns',
        '/admin/campaigns/'.$reservation->trip->campaign->id.'/groups' => $reservation->trip->campaign->name.' - '.country($reservation->trip->country_code),
        '/admin/campaigns/'.$reservation->trip->campaign->id.'/reservations/missionaries' => 'Reservations',
        'active' => $reservation->name
    ]])
    @endbreadcrumbs

@if($reservation->deleted_at)
<div class="bg-primary">
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
            @sidenav(['links' => $pageLinks])
            @endsidenav
        </div>
        <div class="col-xs-12 col-md-7">
            @yield('tab')
        </div>
        <div class="col-xs-12 col-md-3 small">
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

<restore-reservation id="{{ $reservation->id }}"></restore-reservation>
@endsection