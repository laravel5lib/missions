@extends('master')

@section('styles')
  @stack('styles')
@endsection()

@section('layout')
  
  @include('_adminnav')
  

  @yield('content')

  <div class="text-center">
    <hr class="divider">
    <p class="text-muted">Copyright &copy; {{ date('Y') }} Missions.Me. All Rights Reserved.</p>
    <hr class="divider lg inv">
  </div>

@endsection

@section('scripts')
  @stack('scripts')
@endsection