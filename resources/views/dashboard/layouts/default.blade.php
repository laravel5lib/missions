@extends('master')

@section('styles')
  @stack('styles')
@endsection()

@section('layout')
  @include('_adminnav')

  <ul class="nav nav-tabs hidden-xs" style="background: white; margin-bottom: 0">
    <li class="{{ request()->segment(2) == 'settings' ? 'active' : '' }}"><a href="/dashboard/settings"><i class="fa fa-user"></i></a></li>
    <li class="{{ request()->segment(2) == 'reservations' ? 'active' : '' }}"><a href="/dashboard/reservations">My Trip</a></li>
    <li class="{{ request()->segment(2) == 'groups' ? 'active' : '' }}"><a href="/dashboard/groups">My Team</a></li>
    <li class="{{ request()->segment(2) == 'donations' ? 'active' : '' }}"><a href="/dashboard/donations">My Donations</a></li>
    <li class="{{ request()->segment(2) == 'records' ? 'active' : '' }}"><a href="/dashboard/records/passports">My Travel Documents</a></li>
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