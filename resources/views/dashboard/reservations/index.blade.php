@extends('dashboard.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>My Reservations</h3>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="active">
                        <a href="#active" data-toggle="pill"><i class="fa fa-bolt"></i> Active</a>
                    </li>
                    <li role="presentation">
                        <a href="#archive" data-toggle="pill"><i class="fa fa-archive"></i> Archived</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="active">
                        <reservations-list reservations="{{ json_encode($activeReservations) }}"></reservations-list>
                    </div>

                    <div role="tabpanel" class="tab-pane" id="archive">
                        <reservations-list reservations="{{ json_encode($inactiveReservations) }}"></reservations-list>
                    </div>

                </div>

            </div>
        </div>
    </div>
<hr class="divider inv lg">

@endsection