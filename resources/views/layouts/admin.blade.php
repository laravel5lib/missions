@extends('master')

@section('styles')
  @stack('styles')
@endsection()

@section('layout')
  
  @include('_adminnav')

  @hasSection('header')
  <div class="white-header-bg">
    <div class="container-fluid">
      <div class="row">
          <div class="col-xs-12">
            @yield('header')
          </div>
      </div>
    </div>
  </div>
  @endif
  

  @yield('content')

@endsection

@section('scripts')
  @stack('scripts')
@endsection