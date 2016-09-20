@extends('site.layouts.default')

@section('content')
<div class="vid-bg">
  <video muted autoplay loop poster="images/college/home-placeholder.png" class="vid-bg-video">
    <source src="images/college/mmc-vid-bg.webm" type="video/webm">
    <source src="images/college/mmc-vid-bg.mp4" type="video/mp4">
    <source src="images/college/mmc-vid-bg.ogv" type="video/ogg">
  </video>
</div>
<div class="container">
  <div class="row">
    <hr class="divider inv xlg">
    <hr class="divider inv xlg">
    <hr class="divider inv xlg">
    <hr class="divider inv xlg">
    <hr class="divider inv xlg">
    <div class="col-md-6 col-md-offset-3 text-center">
    	<img style="width:200px" src="images/college/mm-college-logo.png" alt="Missions.Me College">
      <h1 class="text-white">Launching students into a life of significance.</h1>
      <hr class="divider inv">
      <a href="#" class="btn btn-white-hollow btn-lg">Apply Now</a>
      <hr class="divider inv xlg">
      <hr class="divider inv xlg">
      <hr class="divider inv xlg">
      <hr class="divider inv xlg">
      <hr class="divider inv xlg">
      </div>
    </div><!-- end col -->
  </div><!-- end row -->
</div><!-- end container -->
<div class="dark-bg-primary">
	<div class="container">
	<div class="content-section">
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2 text-center">
				<h5 class="text-uppercase">Fulfill the great commission</h5>
				<h1 class="text-uppercase">Be The Change</h1>
			</div><!-- end col -->
		</div><!-- end row -->
		<div class="row">
			<div class="col-sm-6">
				<div class="video-outer">
					<div class="video-inner">
						<iframe src="https://player.vimeo.com/video/130333477" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
					</div>
				</div>
			</div><!-- end col -->
			<div class="col-sm-6">
				<div class="video-outer">
					<div class="video-inner">
						<iframe src="https://player.vimeo.com/video/156320313" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
					</div>
				</div>
			</div><!-- end col -->
		</div><!-- end row -->
		<hr class="divider inv">
		<div class="row">
			<div class="col-sm-12 text-center">
				<a href="#" class="btn btn-lg btn-info">Apply Now</a>
			</div>
		</div><!-- end row -->
	</div>
	</div><!-- end container -->
</div><!-- end white-bg -->
@endsection