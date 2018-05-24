@extends('master')

@section('styles')
  @stack('styles')
@endsection()

@section('layout')
@include('_topnav')
<div id="page-wrap"><!-- page-wrap important for sticky footer -->
  @yield('content')
</div>
<!--<div class="interrupter-pop panel panel-default hidden-xs" data-aos="fade-up" id="yearendpopup">
	<figure>
		<img class="img-responsive" src="/images/donate/ye-2017/year-end-thumb.jpg" />
	</figure>
	<div class="interrupter-content">
		<h5>Give A Year End Gift</h5>
		<p class="small">Together we can continue changing nations and lives around the world.</p>
		<a class="btn btn-default-hollow btn-sm" href="{{ url('donate') }}"><i class="fa fa-gift icon-left"></i> Give A Gift</a>
	</div>
	<i class="fa fa-close" id="closepopup"></i>
</div>
</div> -->
  @include('_footernav')
  @include('_footer')
@endsection

@section('scripts')
  @stack('scripts')
@endsection