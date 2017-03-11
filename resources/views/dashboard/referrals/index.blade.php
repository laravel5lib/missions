@extends('dashboard.records.index')

@section('header')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3 class="hidden-xs">My Referrals</h3>
                    <h3 class="visible-xs text-center">My Referrals</h3>
                </div>
                <div class="col-sm-4 text-right hidden-xs">
                    <hr class="divider inv sm">
                    <a href="{{ url('dashboard/records/referrals/create') }}" class="btn btn-primary"><i class="fa fa-plus icon-left"></i> Add Referral</a>
                </div>
                <div class="col-xs-12 text-center visible-xs">
                    <a href="{{ url('dashboard/records/referrals/create') }}" class="btn btn-primary"><i class="fa fa-plus icon-left"></i> Add Referral</a>
                    <hr class="divider inv sm">
                </div>
            </div>
        </div>
    </div>
@stop

@section('tab')
    <referrals-list user-id="{{ auth()->user()->id }}" managing="{{ (auth()->user()->managing()->count()) or 0 }}"></referrals-list>
    <hr class="divider inv lg">
@stop