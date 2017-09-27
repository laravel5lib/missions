@extends('admin.records.index')

@section('header')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-8">
                    <h3 class="hidden-xs">User Referrals</h3>
                    <h3 class="text-center visible-xs">User Referrals</h3>
                </div>
                @can('create', \App\Models\v1\Referral::class)
                <div class="col-sm-4 text-right hidden-xs">
                    <hr class="divider inv sm">
                    <a href="{{ url('admin/records/referrals/create') }}" class="btn btn-primary"><i class="fa fa-plus icon-left"></i> Add Referral</a>
                </div>
                <div class="col-xs-12 text-center visible-xs">
                    <a href="{{ url('admin/records/referrals/create') }}" class="btn btn-primary"><i class="fa fa-plus icon-left"></i> Add Referral</a>
                    <hr class="divider inv sm">
                </div>
                @endcan
            </div>
        </div>
    </div>
@stop

@section('tab')
    <referrals-list></referrals-list>
    <hr class="divider inv lg">
@stop