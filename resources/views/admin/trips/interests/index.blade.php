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

                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                    

                            <div class="col-xs-6">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <label>New Interests</label>
                                        <h3 style="margin:0px">
                                            {{ $percentChanges['received']['count'] }}
                                            <small class="small text-muted">last 14 days</small>
                                        </h3>
                                        <small class="small text-muted">
                                            @if($percentChanges['received']['change'] <= 0)
                                                <span class="badge badge-success">
                                                    <i class="fa fa-arrow-up"></i> {{ $percentChanges['received']['change'] * -1 }}%
                                                </span>
                                            @else
                                                <span class="badge badge-default">
                                                    <i class="fa fa-arrow-down"></i> {{ $percentChanges['received']['change'] }}%
                                                </span>
                                            @endif
                                            change
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <label>New Conversions</label>
                                        <h3 style="margin:0px">
                                            {{ $percentChanges['converted']['count'] }}
                                            <small class="small text-muted">last 14 days</small>
                                        </h3>
                                        <small class="small text-muted">
                                            @if($percentChanges['converted']['change'] <= 0)
                                            <span class="badge badge-success">
                                                <i class="fa fa-arrow-up"></i> {{ $percentChanges['converted']['change'] * -1 }}%
                                            </span>
                                            @else
                                            <span class="badge badge-default">
                                                <i class="fa fa-arrow-down"></i> {{ $percentChanges['converted']['change'] }}%
                                            </span>
                                            @endif
                                            change
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <label>New Unresponsive</label>
                                        <h3 style="margin:0px">
                                            {{ $percentChanges['unresponsive']['count'] }}
                                            <small class="small text-muted">last 14 days</small>
                                        </h3>
                                        <small class="small text-muted">
                                            @if($percentChanges['unresponsive']['change'] <= 0)
                                            <span class="badge badge-default">
                                                <i class="fa fa-arrow-up"></i> {{ $percentChanges['unresponsive']['change'] * -1 }}%
                                            </span>
                                            @else
                                            <span class="badge badge-success">
                                                <i class="fa fa-arrow-down"></i> {{ $percentChanges['unresponsive']['change'] }}%
                                            </span>
                                            @endif
                                            change
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <label>New Declines</label>
                                        <h3 style="margin:0px;">
                                            {{ $percentChanges['declined']['count'] }}
                                            <small class="small text-muted">last 14 days</small>
                                        </h3>
                                        <small class="small text-muted">
                                            @if($percentChanges['declined']['change'] <= 0)
                                            <span class="badge badge-default">
                                                <i class="fa fa-arrow-up"></i> {{ $percentChanges['declined']['change'] * -1 }}%
                                            </span>
                                            @else
                                            <span class="badge badge-success">
                                                <i class="fa fa-arrow-down"></i> {{ $percentChanges['declined']['change'] }}%
                                            </span>
                                            @endif
                                            change
                                        </small>
                                    </div>
                                </div>
                            </div>
                        
                        </div>
                    </div>
                    <div class="col-md-6">
                        <conversion-scoreboard 
                            url="metrics/interests/conversions-by-user?start={{ today()->startOfMonth()->toDateString()}}&end={{ today()->endOfMonth()->toDateString() }}"
                            month="{{ today()->format('F') }}"
                        ></conversion-scoreboard>
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