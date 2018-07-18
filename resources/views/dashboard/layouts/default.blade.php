@extends('master')

@section('styles')
  @stack('styles')
@endsection()

@section('layout')
<div style="min-height: 100vh; display: flex; flex-direction: column;">
  @include('_dashboardnav')
  <div style="flex: 1;">
    @yield('content')
  </div>
  @include('_footernav')
  @include('_footer')
</div>
@endsection

@section('scripts')
  @stack('scripts')
@endsection