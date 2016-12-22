@extends('dashboard.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3 class="hidden-xs">My Referrals <small>&middot; Edit</small></h3>
                    <h3 class="text-center visible-xs">My Referrals<br><small>Edit</small></h3>
                </div>
                <div class="col-sm-4 text-right hidden-xs">
                    <hr class="divider inv sm">
                    <a href="/dashboard/records/referrals" class="btn btn-primary"><i class="fa fa-chevron-left icon-left"></i> Back To All</a>
                </div>
                <div class="col-sm-4 text-center visible-xs">
                    <a href="/dashboard/records/referrals" class="btn btn-primary"><i class="fa fa-chevron-left icon-left"></i> Back To All</a>
                    <hr class="divider inv sm">
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <referral-create-update user-id="{{ auth()->user()->id }}" :is-update="true" id="{{ $id }}"></referral-create-update>
                    </div><!-- end panel-body -->
                </div><!-- end panel -->
            </div>
        </div>
    </div>
    <hr class="divider inv xlg">
@endsection