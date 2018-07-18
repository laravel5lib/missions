@extends('master')

@section('styles')
  @stack('styles')
@endsection()

@section('layout')

<div style="min-height: 100vh; display: flex; flex-direction: column;">
  
    @include('_adminnav') 
    
    <div style="flex: 1;">
        @yield('content')
    </div>
    
    <div class="text-center">
        <hr class="divider">
        <p class="text-muted">Copyright &copy; {{ date('Y') }} Missions.Me. All Rights Reserved.</p>
        <hr class="divider lg inv">
    </div>

</div>

@endsection

@section('scripts')
  @stack('scripts')
@endsection