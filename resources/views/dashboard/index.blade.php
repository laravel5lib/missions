@extends('dashboard.layouts.default')

@section('content')
  <div class="white-header-bg">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <h3>Dashboard</h3>
        </div>
      </div>
    </div>
  </div>
  <hr class="divider inv lg">
  <div class="container">

    <div class="row">

      <div class="col-sm-4">
        @include('dashboard._payments')
      </div>

      <div class="col-sm-4">
        @include('dashboard._requirements')
      </div>

      <div class="col-sm-4">
        @include('dashboard._donations')
      </div>

    </div>
  </div>
@endsection