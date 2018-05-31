@extends('layouts.admin')

@section('content')
<hr class="divider inv lg">

<div class="container">
    <h1>Admin Dashboard</h1>
    <p class="lead">Welcome back, <strong>{{ auth()->user()->name }}</strong></p>
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <div class="panel panel-default" style="border-style: solid; border-width: 4px 0px 0px; border-color: rgb(246, 50, 62);">
                <div class="panel-body text-center"><hr class="divider inv"> 
                    <!-- <i class="fa fa-ticket fa-5x"></i>  -->
                    <h1 class="text-primary text-oswald font-hero">{{ number_format($reservations, 0, '', ',') }}</h1>
                    <h6 class="text-uppercase">Reservations</h6> 
                    <a href="{{ url('/admin/reservations') }}" class="btn btn-sm btn-primary-hollow">Manage</a>
                    <hr class="divider inv">
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-12">
            <div class="panel panel-default" style="border-style: solid; border-width: 4px 0px 0px; border-color: rgb(246, 50, 62);">
                <div class="panel-body text-center"><hr class="divider inv"> 
                    <!-- <i class="fa fa-ticket fa-5x"></i>  -->
                    <h1 class="text-primary text-oswald font-hero">{{ number_format($campaigns, 0, '', ',') }}</h1>
                    <h6 class="text-uppercase">Campaigns</h6> 
                    <a href="{{ url('/admin/campaigns') }}" class="btn btn-sm btn-primary-hollow">Manage</a>
                    <hr class="divider inv">
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-12">
            <div class="panel panel-default" style="border-style: solid; border-width: 4px 0px 0px; border-color: rgb(246, 50, 62);">
                <div class="panel-body text-center"><hr class="divider inv"> 
                    <!-- <i class="fa fa-ticket fa-5x"></i>  -->
                    <h1 class="text-primary text-oswald font-hero">{{ number_format($users, 0, '', ',') }}</h1>
                    <h6 class="text-uppercase">Users</h6> 
                    <a href="{{ url('/admin/users') }}" class="btn btn-sm btn-primary-hollow">Manage</a>
                    <hr class="divider inv">
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-12">
            <div class="panel panel-default" style="border-style: solid; border-width: 4px 0px 0px; border-color: rgb(246, 50, 62);">
                <div class="panel-body text-center"><hr class="divider inv"> 
                    <!-- <i class="fa fa-ticket fa-5x"></i>  -->
                    <h1 class="text-primary text-oswald font-hero">{{ number_format($donors, 0, '', ',') }}</h1>
                    <h6 class="text-uppercase">Donors</h6> 
                    <a href="{{ url('/admin/donors') }}" class="btn btn-sm btn-primary-hollow">Manage</a>
                    <hr class="divider inv">
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-12">
            <div class="panel panel-default" style="border-style: solid; border-width: 4px 0px 0px; border-color: rgb(246, 50, 62);">
                <div class="panel-body text-center"><hr class="divider inv"> 
                    <!-- <i class="fa fa-ticket fa-5x"></i>  -->
                    <h1 class="text-primary text-oswald font-hero">{{ number_format($donations, 0, '', ',') }}</h1>
                    <h6 class="text-uppercase">Donations</h6> 
                    <a href="{{ url('/admin/transactions') }}" class="btn btn-sm btn-primary-hollow">Manage</a>
                    <hr class="divider inv">
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-12">
            <div class="panel panel-default" style="border-style: solid; border-width: 4px 0px 0px; border-color: rgb(246, 50, 62);">
                <div class="panel-body text-center"><hr class="divider inv"> 
                    <!-- <i class="fa fa-ticket fa-5x"></i>  -->
                    <h1 class="text-primary text-oswald font-hero">{{ number_format($groups, 0, '', ',') }}</h1>
                    <h6 class="text-uppercase">Organizations</h6> 
                    <a href="{{ url('/admin/organizations') }}" class="btn btn-sm btn-primary-hollow">Manage</a>
                    <hr class="divider inv">
                </div>
            </div>
        </div>
    </div>
    <hr class="divider xlg">
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <div class="panel panel-default" style="border-style: solid; border-width: 4px 0px 0px; border-color: rgb(246, 50, 62);">
            <div class="panel-body text-center"><hr class="divider inv"> <img src="/images/coordinators/docs-icon.png" width="50px" height="50px"> <h6 class="text-uppercase">Reports</h6> <p class="small hidden-xs">Download export files you have generated.</p> <a href="{{ url('/admin/reports') }}" class="btn btn-sm btn-primary-hollow">View</a> <hr class="divider inv"></div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-12">
            <div class="panel panel-default" style="border-style: solid; border-width: 4px 0px 0px; border-color: rgb(246, 50, 62);">
            <div class="panel-body text-center"><hr class="divider inv"> <img src="/images/coordinators/env-icon.png" width="50px" height="50px"> <h6 class="text-uppercase">Leads</h6> <p class="small hidden-xs">View data collected from lead generating forms.</p> <a href="{{ url('/admin/leads') }}" class="btn btn-sm btn-primary-hollow">View</a> <hr class="divider inv"></div>
            </div>
        </div>
    </div>
</div>
@endsection