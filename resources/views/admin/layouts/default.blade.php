@extends('master')

@section('styles')
  @stack('styles')
@endsection()

@section('layout')
  @include('admin.partials._toolbar')
  @include('_topnav')
  <div id="admin-page-wrap"><!-- page-wrap important for sticky footer -->
    @yield('content')
  </div>
  @include('_footer')
@endsection

@section('scripts')
  @stack('scripts')
@endsection