@extends('site.layouts.default')

@section('content')
<div class="vid-bg">
  <video muted autoplay loop poster="video/video-placeholder.png" class="vid-bg-video">
    <source src="video/1n1d-bg-loop.webm" type="video/webm">
    <source src="video/1n1d-bg-loop.mp4" type="video/mp4">
    <source src="video/1n1d-bg-loop.ogv" type="video/ogg">
  </video>
</div>
<div class="container">
  <div class="row">
    <hr class="divider inv xlg">
    <hr class="divider inv xlg">
    <div class="col-md-6 col-md-offset-3">
      <h5 class="text-white text-uppercase text-center">Welcome Back To Missions.Me</h5>
      <hr class="divider inv">
        <login></login>
      <hr class="divider inv xlg">
      <hr class="divider inv xlg">
      </div>
    </div><!-- end col -->
  </div><!-- end row -->
</div><!-- end container -->
@endsection