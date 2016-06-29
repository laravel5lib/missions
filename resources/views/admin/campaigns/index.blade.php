@extends('admin.layouts.default')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>
                    Current Campaigns
                    <a href="/admin/campaigns/create" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> New</a>
                </h3>

                <ul class="nav nav-pills">
                    <li role="presentation" class="active"><a href="#active" data-toggle="pill"><i
                                    class="fa fa-bolt"></i> Active</a></li>
                    <li role="presentation"><a href="#archive" data-toggle="pill"><i class="fa fa-archive"></i> Archived</a>
                    </li>
                </ul>

                <hr>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="active">
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
                                        @if($campaign->published_at and $campaign->published_at->isFuture())
                                            <i class="fa fa-calendar"></i> Scheduled
                                        @elseif($campaign->published_at and $campaign->published_at->isPast())
                                            <i class="fa fa-check"></i> Published
                                        @else
                                            <i class="fa fa-pencil"></i> Draft
                                        @endif
                                    </td>
                                    <td><a href="/admin/campaigns/{{ $campaign->id }}"><i class="fa fa-pencil"></i></a>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="archive">
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
                                        @if($campaign->published_at and $campaign->published_at->isFuture())
                                            <i class="fa fa-calendar"></i> Scheduled
                                        @elseif($campaign->published_at and $campaign->published_at->isPast())
                                            <i class="fa fa-check"></i> Published
                                        @else
                                            <i class="fa fa-pencil"></i> Draft
                                        @endif
                                    </td>
                                    <td>
                                        <a href="/admin/campaigns/{{ $campaign->id }}"><i class="fa fa-eye"></i></a>
                                        <a href="/admin/campaigns/{{ $campaign->id }}/edit"><i class="fa fa-pencil"></i></a>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection