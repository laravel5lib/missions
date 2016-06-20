@extends('admin.layouts.default')

@section('content')

<div class="container">
  <div class="row">
    <div class="col-sm-3">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h5><i class="fa fa-cog"></i> Manage</h5>
        </div>
        <div class="list-group">
          <a href="#" class="list-group-item active">Details</a></a>
          <a href="#" class="list-group-item">Trips</a></a>
          <a href="#" class="list-group-item">Regions</a></a>
          <a href="#" class="list-group-item">Transports</a></a>
        </div>
      </div>
      
      <div class="panel panel-default">
        <div class="panel-heading">
          <h5><i class="fa fa-line-chart"></i> Statistics</h5>
        </div>
        <div class="list-group">
          <a href="#" class="list-group-item">Overview</a></a>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading">
          <h5><i class="fa fa-file"></i> Interactions</h5>
        </div>
        <div class="list-group">
          <a href="#" class="list-group-item">Sites</a></a>
          <a href="#" class="list-group-item">Decisions</a></a>
          <a href="#" class="list-group-item">Exams</a></a>
        </div>
      </div>

    </div>
    <div class="col-sm-9">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h4>Campaign Details</h4>
        </div>
        <div class="panel-body">
          ...
        </div>
        <div class="panel-footer text-muted small">
          Campaign ID: {{ $campaign->id }}
        </div>
      </div>

    </div>
  </div>
</div>

@endsection