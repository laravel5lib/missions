@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row hidden-xs">
            <div class="col-sm-8">
                <h3>Campaigns</h3>
            </div>
            <div class="col-sm-4 text-right">
                <hr class="divider inv sm">
                <a href="/admin/campaigns/create" class="btn btn-primary"><i class="fa fa-plus icon-left"></i> New</a>
            </div>
        </div>
        <div class="row visible-xs">
            <div class="col-sm-8 text-center">
                <h3>Campaigns</h3>
            </div>
            <div class="col-sm-4 text-center">
                <a href="/admin/campaigns/create" class="btn btn-primary"><i class="fa fa-plus icon-left"></i> New</a>
                <hr class="divider inv sm">
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="active"><a href="#active" data-toggle="tab"><i
                                    class="fa fa-bolt"></i> Active</a></li>
                    <li role="presentation"><a href="#archive" data-toggle="tab"><i class="fa fa-archive"></i> Archived</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="active">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Country</th>
                                        <th>Dates</th>
                                        <th>Name</th>
                                        <th>Groups</th>
                                        <th>Status</th>
                                        <th>Manage</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($campaigns as $campaign)
                                        @if($campaign->ended_at->isFuture())
                                        <tr>
                                            <td>{{ country($campaign->country_code) }}</td>
                                            <td>{{ $campaign->started_at->format('M j, Y').' - '.$campaign->ended_at->format('M j, Y') }}</td>
                                            <td>{{ $campaign->name }}</td>
                                            <td>{{ $campaign->groups()->count() }} <i class="fa fa-group"></i></td>
                                            <td>
                                                @if($campaign->status == 'Scheduled')
                                                    <i class="fa fa-calendar"></i> Scheduled
                                                @elseif($campaign->status == 'Published')
                                                    <i class="fa fa-check"></i> Published
                                                @else
                                                    <i class="fa fa-pencil"></i> Draft
                                                @endif
                                            </td>
                                            <td class="text-center"><a href="/admin/campaigns/{{ $campaign->id }}"><i class="fa fa-gear"></i></a>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div><!-- end panel-body -->
                        </div><!-- end panel -->
                    </div>
                    <div role="tabpanel" class="tab-pane" id="archive">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Country</th>
                                        <th>Dates</th>
                                        <th>Name</th>
                                        <th>Groups</th>
                                        <th>Status</th>
                                        <th>Manage</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($campaigns as $campaign)
                                        @if($campaign->ended_at->isPast())
                                        <tr>
                                            <td>{{ country($campaign->country_code) }}</td>
                                            <td>{{ $campaign->started_at->format('M j, Y').' - '.$campaign->ended_at->format('M j, Y') }}</td>
                                            <td>{{ $campaign->name }}</td>
                                            <td>{{ $campaign->groups()->count() }} <i class="fa fa-group"></i></td>
                                            <td>
                                                @if($campaign->status == 'Scheduled')
                                                    <i class="fa fa-calendar"></i> Scheduled
                                                @elseif($campaign->status == 'Published')
                                                    <i class="fa fa-check"></i> Published
                                                @else
                                                    <i class="fa fa-pencil"></i> Draft
                                                @endif
                                            </td>
                                            <td class="text-center"><a href="/admin/campaigns/{{ $campaign->id }}"><i class="fa fa-gear"></i></a>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div><!-- end panel-body -->
                        </div><!-- end panel -->
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection