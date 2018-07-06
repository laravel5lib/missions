@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/campaigns' => 'Campaigns',
        'admin/campaigns/'.$campaign->id => $campaign->name.' - '.country($campaign->country_code),
        'active' => 'Reservations'
    ]])
    @endbreadcrumbs
    
    <hr class="divider inv lg">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">
                @include('admin.reservations._sidenav')
            </div>
            <div class="col-sm-10">

                <div class="row text-center">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Received</h5>
                            </div>
                            <div class="panel-body">
                            <h1>
                                {{ $percentChanges['received']['count'] }}

                                @if($percentChanges['received']['change'] <= 0)
                                <span class="badge badge-success">
                                    <i class="fa fa-arrow-up"></i> {{ $percentChanges['received']['change'] * -1 }}%
                                </span>
                                @else
                                <span class="badge badge-default">
                                    <i class="fa fa-arrow-down"></i> {{ $percentChanges['received']['change'] }}%
                                </span>
                                @endif
                            </h1>
                            <small class="small text-muted">vs past 7 days</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Converted</h5>
                            </div>
                            <div class="panel-body">
                                <h1>
                                    {{ $percentChanges['converted']['count'] }}
                                    @if($percentChanges['converted']['change'] <= 0)
                                    <span class="badge badge-success">
                                        <i class="fa fa-arrow-up"></i> {{ $percentChanges['converted']['change'] * -1 }}%
                                    </span>
                                    @else
                                    <span class="badge badge-default">
                                        <i class="fa fa-arrow-down"></i> {{ $percentChanges['converted']['change'] }}%
                                    </span>
                                    @endif
                                </h1>
                                <small class="small text-muted">vs past 7 days</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>No Response</h5>
                            </div>
                            <div class="panel-body">
                                <h1>
                                    {{ $percentChanges['unresponsive']['count'] }}

                                    @if($percentChanges['unresponsive']['change'] <= 0)
                                    <span class="badge badge-default">
                                        <i class="fa fa-arrow-up"></i> {{ $percentChanges['unresponsive']['change'] * -1 }}%
                                    </span>
                                    @else
                                    <span class="badge badge-success">
                                        <i class="fa fa-arrow-down"></i> {{ $percentChanges['unresponsive']['change'] }}%
                                    </span>
                                    @endif
                                </h1>
                                <small class="small text-muted">vs past 7 days</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Declined</h5>
                            </div>
                            <div class="panel-body">
                                <h1>
                                    {{ $percentChanges['declined']['count'] }}

                                    @if($percentChanges['declined']['change'] <= 0)
                                    <span class="badge badge-default">
                                        <i class="fa fa-arrow-up"></i> {{ $percentChanges['declined']['change'] * -1 }}%
                                    </span>
                                    @else
                                    <span class="badge badge-success">
                                        <i class="fa fa-arrow-down"></i> {{ $percentChanges['declined']['change'] }}%
                                    </span>
                                    @endif
                                </h1>
                                <small class="small text-muted">vs past 7 days</small>
                            </div>
                        </div>
                    </div>
                </div>

                <interest-list campaign-id="{{ $campaign->id }}"
                                cache-key="admin.campaign.{{$campaign->id}}.interests.interestList"
                                :totals="{{ json_encode($totals) }}"
                ></interest-list>
            </div>
        </div>
    </div>
@endsection