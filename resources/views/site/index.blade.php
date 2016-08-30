@extends('site.layouts.default')

@section('content')
<div class="vid-bg">
  <video muted autoplay loop poster="video/video-placeholder.png" class="vid-bg-video">
    <source src="video/mm-homepage-bg.webm" type="video/webm">
    <source src="video/mm-homepage-bg.mp4" type="video/mp4">
    <source src="video/mm-homepage-bg.ogv" type="video/ogg">
  </video>
</div>
<div class="container">
  <div class="row">
    <hr class="divider inv xlg">
    <hr class="divider inv xlg">
    <hr class="divider inv xlg">
    <hr class="divider inv xlg">
    <div class="col-md-6 col-md-offset-3 text-center">
      <h1 class="text-white">Where you can change the world.</h1>
      <hr class="divider inv">
      <a href="/campaigns" class="btn btn-white-hollow btn-lg">Go On A Trip</a>
      <a href="/fundraisers" class="btn btn-white-hollow btn-lg">Support A Cause</a>
      <hr class="divider inv xlg">
      <hr class="divider inv xlg">
      <hr class="divider inv xlg">
      <hr class="divider inv xlg">
      </div>
    </div><!-- end col -->
  </div><!-- end row -->
</div><!-- end container -->
@endsection