@extends('dashboard.layouts.default')

@section('content')
<hr class="divider inv lg">

<div class="container">
    <h1>Dashboard</h1>
    <p class="lead">Welcome back, <strong>{{ auth()->user()->name }}</strong></p>

    <div class="row">

        @if(auth()->user()->managing()->count())
        <div class="col-sm-6 col-xs-12">
            <div class="panel panel-default" style="border-style: solid; border-width: 4px 0px 0px; border-color: rgb(246, 50, 62);">
            <div class="panel-body text-center"><hr class="divider inv"> <i class="fa fa-users fa-3x"></i> <h6 class="text-uppercase">My Team</h6> <p class="small hidden-xs">Manage your organization's team members.</p> <a href="{{ url('/dashboard/groups') }}" class="btn btn-sm btn-primary-hollow">Get Started</a> <hr class="divider inv"></div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-12">
            <div class="panel panel-default" style="border-style: solid; border-width: 4px 0px 0px; border-color: rgb(246, 50, 62);">
            <div class="panel-body text-center"><hr class="divider inv"> <i class="fa fa-file-excel-o fa-3x"></i> <h6 class="text-uppercase">Reports</h6> <p class="small hidden-xs">Download export files you have generated.</p> <a href="{{ url('/dashboard/reports') }}" class="btn btn-sm btn-primary-hollow">View</a> <hr class="divider inv"></div>
            </div>
        </div>
        @endif


        <div class="col-sm-6 col-xs-12">
            <div class="panel panel-default" style="border-style: solid; border-width: 4px 0px 0px; border-color: rgb(246, 50, 62);">
            <div class="panel-body text-center"><hr class="divider inv"> <i class="fa fa-map-o fa-3x"></i> <h6 class="text-uppercase">My Trip</h6> <p class="small hidden-xs">View and manage your reservations.</p> <a href="{{ url('/dashboard/reservations') }}" class="btn btn-sm btn-primary-hollow">Get Started</a> <hr class="divider inv"></div>
            </div>
        </div>

        <div class="col-sm-6 col-xs-12">
            <div class="panel panel-default" style="border-style: solid; border-width: 4px 0px 0px; border-color: rgb(246, 50, 62);">
            <div class="panel-body text-center"><hr class="divider inv"> <i class="fa fa-flag-o fa-3x"></i> <h6 class="text-uppercase">My Fundraiser</h6> <p class="small hidden-xs">View and manage your fundraisers.</p> <a href="{{ url('/dashboard/reservations?target=funding') }}" class="btn btn-sm btn-primary-hollow">Get Started</a> <hr class="divider inv"></div>
            </div>
        </div>

        <div class="col-sm-6 col-xs-12">
            <div class="panel panel-default" style="border-style: solid; border-width: 4px 0px 0px; border-color: rgb(246, 50, 62);">
            <div class="panel-body text-center"><hr class="divider inv"> <i class="fa fa-file-text-o fa-3x"></i> <h6 class="text-uppercase">Travel Requirements</h6> <p class="small hidden-xs">Passports, medical releases, and more.</p> <a href="{{ url('/dashboard/reservations?target=requirements') }}" class="btn btn-sm btn-primary-hollow">Get Started</a> <hr class="divider inv"></div>
            </div>
        </div>

        <div class="col-sm-6 col-xs-12">
            <div class="panel panel-default" style="border-style: solid; border-width: 4px 0px 0px; border-color: rgb(246, 50, 62);">
            <div class="panel-body text-center"><hr class="divider inv"> <i class="fa fa-user fa-3x"></i> <h6 class="text-uppercase">Profile Settings</h6> <p class="small hidden-xs">Make changes to your account and public profile.</p> <a href="{{ url('/dashboard/settings') }}" class="btn btn-sm btn-primary-hollow">Get Started</a> <hr class="divider inv"></div>
            </div>
        </div>

    </div>

</div>
@endsection