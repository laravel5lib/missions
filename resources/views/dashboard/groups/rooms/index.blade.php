@extends('dashboard.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-8">
                        <h3 class="hidden-xs">Rooming Plans</h3>
                        <h3 class="text-center visible-xs">Rooming Plans</h3>
                    </div>
                    <div class="col-sm-4 text-right">
                        {{--<action-select :normal="false" :options="[]" :api="true" search-route="campaigns" text="Change Campaign" event="campaign-scope" :auto-select-first="true"></action-select>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
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
            </div>
        </div>
        <rooming-manager user-id="{{ Auth::user()->id }}" group-id="{{ $groupId }}"></rooming-manager>
        <hr class="divider inv xlg">
    </div>
@endsection

@section('tour')

@endsection