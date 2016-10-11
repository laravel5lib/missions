@extends('site.layouts.default')
@section('scripts')
<script>
$('.launch-modal').on('click', function(e){
    e.preventDefault();
    $( '#' + $(this).data('video-modal') ).modal();
});
</script>
@endsection
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
    <div class="col-md-8 col-md-offset-2 text-center">
      <h1 class="text-white text-hero animate fadeInUp one">Where <span class="text-primary animate fadeInUp two">you</span> can change the world.</h1>
      <hr class="divider inv">
      <a href="/campaigns" class="btn btn-primary animate fadeInUp two">Go On A Trip</a>
      <a href="/fundraisers" class="btn btn-white-hollow animate fadeInUp three">Support A Cause</a>
      <hr class="divider inv xlg">
      <hr class="divider inv xlg">
      <hr class="divider inv xlg">
      <hr class="divider inv xlg">
      </div>
    </div><!-- end col -->
  </div><!-- end row -->
</div><!-- end container -->
<div class="gray-darker-bg animate fadeInUp three" style="background-color:rgba(62, 62, 62, 0.8);">
  <div class="container">
    <div class="content-section" style="padding:30px 0px;">
      <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1 text-center">
          <h5 class="text-uppercase">Missions.Me empowers people to change the world.<hr class="divider inv visible-sm visible-xs"> <a class="btn btn-primary btn-sm launch-modal" data-toggle="modal" data-target="#video-modal">Watch Video</a></h5>
        </div><!-- end col -->
      </div><!-- end row -->
    </div><!-- end content-section -->
  </div><!-- end container -->
</div><!-- end white-bg -->
<div class="gray-lighter-bg">
  <div class="container">
    <div class="content-section">
      <div class="row">
        <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 text-center">
          <h1>Take Your Group</h1>
          <hr class="divider red-small">
          <hr class="divider inv">
          <p>Missions.Me specializes in taking groups around the world on life-changing missions experiences. Missions.Me can provide your group with custom missions trips created especially for your group.</p>
          <hr class="divider inv">
          <a href="#" class="btn btn-info">Take Your Group</a>
        </div><!-- end col -->
      </div><!-- end row -->
    </div><!-- end content-section -->
  </div><!-- end container -->
</div><!-- end white-bg -->
<div class="angel-house-bg">
  <div class="container">
    <div class="content-section">
      <div class="row">
        <div class="col-sm-6 home-half-section">
          <h1 class="dash-trailing">Angel House</h1>
          <p class="large-type">Our mission is to bring abandoned children from the slum to safety and into the loving arms of caretakers that will educate, love and raise them to become future leaders in their generation.</p>
          <hr class="divider inv">
          <a href="/orphans" class="btn btn-white-hollow">Learn More</a>
        </div><!-- end col -->
        <hr class="divider inv lg visible-xs visible-sm">
      </div><!-- end row -->
    </div><!-- end content-section -->
  </div><!-- end container -->
</div><!-- end white-bg -->
<div class="gray-lighter-bg">
  <div class="container">
    <div class="content-section">
      <div class="row">
        <div class="col-md-6">
          <img class="img-responsive img-rounded" src="../images/home/college-home-2.jpg">
        </div><!-- end col -->
        <div class="col-md-6 home-half-section">
          <h1 class="dash-trailing home-college-header">Missions.Me College</h1>
          <p class="large-type">We believe success is a byproduct of significance and strive to develop a premiere training facility where individuals are equipped with the tools to live impact filled lives.</p>
          <hr class="divider inv">
          <a href="/college" class="btn btn-primary">Learn More</a>
        </div><!-- end col -->
      </div><!-- end row -->
    </div><!-- end content-section -->
  </div><!-- end container -->
</div><!-- end white-bg -->
<div class="change-world-bg">
  <div class="container">
    <div class="content-section">
      <div class="row">
        <div class="col-sm-12 text-center">
          <h1>Change The World</h1>
          <a href="#" class="btn btn-primary btn-lg">Create Your Account</a>
          <h5 class="text-uppercase"><a href="#">Or Donate</a></h5>
        </div><!-- end col -->
      </div><!-- end row -->
    </div><!-- end content-section -->
  </div><!-- end container -->
</div><!-- end white-bg -->
<!-- MODAL -->
<div class="modal video-modal fade" id="video-modal" tabindex="-1" role="dialog" aria-labelledby="modal-video-label">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-video">
                    <div class="video-outer">
                      <div class="video-inner">
                        <iframe src="https://player.vimeo.com/video/179952979" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection