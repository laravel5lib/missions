@extends('dashboard.layouts.default')

@section('content')
  <div class="white-header-bg">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <h3 class="hidden-xs">Dashboard</h3>
          <h3 class="visible-xs text-center">Dashboard</h3>
        </div>
      </div>
    </div>
  </div>
  <hr class="divider inv lg">
  <div class="container">
    <div class="alert alert-success">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 style="margin-bottom:5px;">Welcome to your new dashboard</h4>
      <p class="small">Access the most important information about your account in one place</p>
    </div>
    <div class="row">
      <div class="col-sm-6">
        @include('dashboard._payments')
      </div>

      <div class="col-sm-6">
        @include('dashboard._requirements')
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        @include('dashboard._donations')
      </div>

      <div class="col-sm-6">
        @include('dashboard._featurednews')
      </div>
    </div>
  </div>
  <hr class="divider inv xlg">
@endsection