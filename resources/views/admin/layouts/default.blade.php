@extends('master')

@section('styles')
  @stack('styles')
@endsection()

@section('layout')
<div id="page-wrap"><!-- page-wrap important for sticky footer -->
  @include('admin.partials._toolbar')
  @include('_topnav')
  @yield('content')
</div>
  @include('_footer')
@endsection

@section('scripts')
  @stack('scripts')
@endsection