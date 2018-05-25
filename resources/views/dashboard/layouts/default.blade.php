@extends('master')

@section('styles')
  @stack('styles')
@endsection()

@section('layout')
  @include('_dashboardnav')

  <!-- <ul class="nav nav-tabs hidden-xs" style="background: #eee; margin-bottom: 0">
    <li class="{{ !request()->segment(2) ? 'active' : '' }}"><a href="/dashboard"><i class="fa fa-home"></i></a></li>
    <li class="{{ request()->segment(2) == 'settings' ? 'active' : '' }}"><a href="/dashboard/settings">Profile Settings</a></li>
    <li class="{{ request()->segment(2) == 'reservations' ? 'active' : '' }}"><a href="/dashboard/reservations">My Trip</a></li>
    <li class="{{ request()->segment(2) == 'records' ? 'active' : '' }}"><a href="/dashboard/records/passports">My Travel Documents</a></li>

    @if(auth()->user()->managing()->count())
    <li class="{{ request()->segment(2) == 'groups' ? 'active' : '' }}"><a href="/dashboard/groups">My Team</a></li>
    <li class="{{ request()->segment(2) == 'reports' ? 'active' : '' }}"><a href="/dashboard/reports">My Reports</a></li>
    @endif

  </ul> -->



  <!-- <div id="dash-page-wrap"> -->
    @yield('content')
  <!-- </div> -->
  @include('_footernav')
  @include('_footer')
@endsection

@section('scripts')
  @stack('scripts')
@endsection