@extends('master')

@section('styles')
  @stack('styles')
@endsection()

@section('layout')
  @include('_adminnav')


  <ul class="nav nav-tabs hidden-xs" style="background: white">
    <li role="presentation"><a href="#"><i class="fa fa-home"></i></a></li>
    <li role="presentation" class="active"><a href="/dashboard/reservations">My Trip</a></li>
    <li role="presentation"><a href="#">My Team</a></li>
    <li role="presentation"><a href="#">My Donations</a></li>
    <li role="presentation"><a href="/dashboard/records/passports">My Travel Documents</a></li>
  </ul>



  <!-- <div id="dash-page-wrap"> -->
    @yield('content')
  <!-- </div> -->
  @include('_footernav')
  @include('_footer')
@endsection

@section('scripts')
  @stack('scripts')
@endsection