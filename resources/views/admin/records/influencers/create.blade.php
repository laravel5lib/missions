@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3 class="hidden-xs">User Influencer Applications <small>&middot; Create</small></h3>
                    <h3 class="visible-xs text-center">User Influencer Applications<br><small>Create</small></h3>
                </div>
                <div class="col-sm-4 text-right hidden-xs">
                    <hr class="divider inv sm">
                    <a onclick="window.history.back()" class="btn btn-primary"><i class="fa fa-chevron-left icon-left"></i> Back</a>
                </div>
                <div class="col-sm-4 text-center visible-xs">
                    <a onclick="window.history.back()" class="btn btn-primary"><i class="fa fa-chevron-left icon-left"></i> Back</a>
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
                        <influencer-questionnaire-create-update :for-admin="true"></influencer-questionnaire-create-update>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv xlg">
@endsection