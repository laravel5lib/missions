@extends('admin.layouts.default')

@section('content')

  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3>Current Campaigns</h3>
          </div>
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
              <tr>
                <td>{{ country($campaign->country_code) }}</td>
                <td>{{ $campaign->started_at->format('M j, Y').' - '.$campaign->ended_at->format('M j, Y') }}</td>
                <td>{{ $campaign->name }}</td>
                <td>{{ $campaign->groups()->count() }} <i class="fa fa-group"></i></td>
                <td>
                  @if($campaign->published_at->isFuture())
                  <i class="fa fa-calendar"></i> Scheduled
                  @elseif($campaign->published_at->isPast())
                  <i class="fa fa-check"></i> Published
                  @else
                  <i class="fa fa-pencil"></i> Draft
                  @endif
                </td>
                <td><a href="/admin/campaigns/{{ $campaign->id }}"><i class="fa fa-pencil"></i></a></td>
              </tr>
              @endforeach
            </tbody>
          </table>
          </div>
          <div class="panel-footer text-right">
            <a href="#"><i class="fa fa-archive"></i> Archive
            <a href="#"><i class="fa fa-plus"></i> New
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection