@extends('dashboard.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-8">
                        <h3 class="hidden-xs"><listen-text event="update-title"></listen-text> <span class="small">&middot; Rooming Plans</span></h3>
                        <h3 class="text-center visible-xs"><listen-text event="update-title"></listen-text> <br><span class="small">Rooming Plans</span></h3>
                    </div>
                    <div class="col-sm-4 text-right">
                        <hr class="divider inv sm">
                        <action-select :normal="false" :options="[]" :api="true" search-route="campaigns" text="Change Campaign" event="campaign-scope" :auto-select-first="true"></action-select>
                        <hr class="divider inv sm visible-xs">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dark-bg-primary">
        <div class="container">
            <div class="col-sm-8 col-md-7 text-center">
                <hr class="divider inv">
                <h4 style="margin-top:13px;" class="hidden-xs">Select a rooming plan or create a new one.</h4>
                <h4 style="margin-top:13px;" class="visible-xs text-center">Select a rooming plan or create a new one.</h4>
                <hr class="divider inv sm">
                <hr class="divider inv sm hidden-xs">
            </div>
            <div class="col-sm-4 col-md-5 hidden-xs">
                <hr class="divider inv sm">
                <action-dropdown-select api search-route="rooming/plans" text="Rooming Plan" event="plan-scope" :options="[]"></action-dropdown-select>
                <action-trigger text="Create Plan" event="create-plan" type="btn-white-hollow"></action-trigger>
                <hr class="divider inv sm">
            </div>
            <div class="col-sm-4 visible-xs text-center">
                <hr class="divider inv sm">
                <action-dropdown-select api search-route="rooming/plans" text="Rooming Plan" event="plan-scope" :options="[]"></action-dropdown-select>
                <hr class="divider inv sm">
                <action-trigger text="Create Plan" event="create-plan" type="btn-white-hollow"></action-trigger>
                <hr class="divider inv sm">
            </div>
        </div><!-- end container -->
    </div>
    <hr class="divider inv lg">
@include('dashboard.groups.tabs', ['active' => 'rooming'])
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="text-uppercase"><a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Room Management Made Simple <span class="pull-right"><i class="fa fa-close"></i></span></a></h6>
                    </div><!-- end panel-heading -->
                    <div class="panel-body panel-collapse collapse in" id="collapseOne">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4">
                                <p class="small">Missions.Me has created a system to help you manage your teams. Add missionaries to specific teams using this simple tool.</p>
                            </div><!-- end col -->
                            <div class="col-xs-12 col-sm-8">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h6 class="text-uppercase">Follow these simple steps</h6>
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        <p class="small"><strong>Step 1</strong> Create and name your new team.</p>
                                        <p class="small"><strong>Step 2</strong> Assign Team Members using the dropdown menu on reservations.</p>
                                        <p class="small"><strong>Step 3</strong> Assign a Team Leader.</p>
                                    </div><!-- end col -->
                                    <div class="col-xs-12 col-sm-6">
                                        <p class="small"><strong>Step 4</strong> Assign a Group Leader.</p>
                                        <p class="small"><strong>Step 5</strong> Add Team Members to groups.</p>
                                        <p class="small"><strong>Step 6</strong> Create more groups and teams then repeat!</p>
                                    </div><!-- end col -->
                                </div><!-- end row -->
                            </div><!-- end col -->
                        </div><!-- end row -->
                    </div><!-- end panel-body -->
                </div><!-- end panel -->
            </div><!-- end col -->
        </div><!-- end row -->
        <rooming-manager user-id="{{ Auth::user()->id }}" group-id="{{ $groupId }}"></rooming-manager>
        <hr class="divider inv xlg">
    </div><!-- end container -->
@endsection

@section('tour')

@endsection