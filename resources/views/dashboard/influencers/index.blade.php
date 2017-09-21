@extends('dashboard.records.index')

@section('header')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3 class="hidden-xs">My Influencer Applications</h3>
                    <h3 class="visible-xs text-center">My Influencer Applications</h3>
                </div>
                <div class="col-sm-4 text-right hidden-xs">
                    <hr class="divider inv sm">
                    <a href="{{ url('dashboard/records/influencers/create') }}" class="btn btn-primary"><i class="fa fa-plus icon-left"></i> Add Application</a>
                </div>
                <div class="col-xs-12 text-center visible-xs">
                    <a href="{{ url('dashboard/records/influencers/create') }}" class="btn btn-primary"><i class="fa fa-plus icon-left"></i> Add Application</a>
                    <hr class="divider inv sm">
                </div>
            </div>
        </div>
    </div>
@stop

@section('tab')
    <influencer-questionnaires-list user-id="{{ auth()->user()->id }}"></influencer-questionnaires-list>
    <hr class="divider inv xlg">
@stop