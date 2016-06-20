@extends('master')

@section('styles')
  @stack('styles')
@endsection()

@section('layout')
@include('_topnav')
<div id="page-wrap"><!-- page-wrap important for sticky footer -->
  @yield('content')
</div>
  @include('_footernav')
  @include('_footer')
@endsection

@section('scripts')
  @stack('scripts')
@endsection