@extends('dashboard.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="hidden-xs">My Teams</h3>
                    <h3 class="text-center visible-xs">My Teams</h3>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <accordion :one-at-atime="true" type="default">
                    <panel is-open header="Tutorial Details">
                        <div class="alert alert-default alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Tip 1!</strong> Don't Worry.
                        </div>
                        <div class="alert alert-default alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Tip 2!</strong> Be Happy.
                        </div>
                    </panel>
                </accordion>
            </div>
            <div class="col-md-6">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="active">
                        <a href="#members" data-toggle="pill"><i class="fa fa-bolt"></i> Members</a>
                    </li>
                    <li role="presentation">
                        <a href="#teamdetails" data-toggle="pill"><i class="fa fa-archive"></i> Team Details</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="members">

                    </div>

                    <div role="tabpanel" class="tab-pane" id="teamdetails">

                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="active">
                        <a href="#reservations" data-toggle="pill"><i class="fa fa-bolt"></i> Reservations</a>
                    </li>
                    <li role="presentation">
                        <a href="#teams" data-toggle="pill"><i class="fa fa-archive"></i> Teams</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="reservations">

                    </div>

                    <div role="tabpanel" class="tab-pane" id="teams">

                    </div>

                </div>
            </div>
            <div class="col-sm-12">

            </div>
        </div>
        <hr class="divider inv lg">
    </div>
@endsection

@section('tour')

@endsection