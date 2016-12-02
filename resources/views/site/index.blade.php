@extends('site.layouts.default')
@section('scripts')
<script>
$('.launch-modal').on('click', function(e){
    e.preventDefault();
    $( '#' + $(this).data('video-modal') ).modal();
});
$('.video-modal').on('hide.bs.modal', function(e) {    
    var $if = $(e.delegateTarget).find('iframe');
    var src = $if.attr("src");
    $if.attr("src", '/empty.html');
    $if.attr("src", src);
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
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2 text-center">
        <div class="vid-bg-caption">
          <h1 class="text-white text-hero" data-aos="fade-up">Where <span class="text-primary" data-aos="zoom-in" data-aos-delay="400">you</span> can change the world.</h1>
          <hr class="divider inv">
          <a href="{{ url('campaigns') }}" class="btn btn-primary hidden-xs" data-aos="fade-up" data-aos-delay="200">Go On A Trip</a>
          <a href="{{ url('fundraisers') }}" class="btn btn-white-hollow hidden-xs" data-aos="fade-up" data-aos-delay="300">Support A Cause</a>
          <div class="row visible-xs">
            <div class="col-xs-8 col-xs-offset-2">
              <a href="{{ url('campaigns') }}" class="btn btn-primary btn-block" data-aos="fade-up" data-aos-delay="200">Go On A Trip</a>
              <hr class="divider inv visible-xs">
              <a href="{{ url('fundraisers') }}" class="btn btn-white-hollow btn-block" data-aos="fade-up" data-aos-delay="300">Support A Cause</a>
            </div>
          </div>
        </div><!-- end vid-bg-caption -->
      </div><!-- end col -->
    </div><!-- end row -->
  </div><!-- end container -->
</div><!-- end vid-bg -->

<div class="gray-darker-bg">
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
          <h1 data-aos="fade-up">Take Your Group</h1>
          <hr data-aos="fade-up" class="divider red-small">
          <hr class="divider inv">
          <p data-aos="fade-up">Missions.Me specializes in taking groups around the world on life-changing missions experiences. Missions.Me can provide your group with custom missions trips created especially for your group.</p>
          <hr class="divider inv">
          <a data-aos="fade-up" href="{{ url('groups') }}" class="btn btn-info">Take Your Group</a>
        </div><!-- end col -->
      </div><!-- end row -->
    </div><!-- end content-section -->
  </div><!-- end container -->
</div><!-- end white-bg -->
<div class="gray-darker-bg">
  <div class="container">
    <div class="content-section">
      <div class="row">
        <div class="col-sm-6">
          <img data-aos="fade-right" class="img-responsive img-rounded" src="../images/home/1nation1day.jpg">
        </div><!-- end col -->
        <hr class="divider inv lg visible-xs visible-sm">
        <div class="col-sm-6 home-half-section">
          <h1 class="dash-trailing" data-aos="fade-left">1Nation1Day</h1>
          <p class="large-type" data-aos="fade-left">The mission of 1Nation1Day is to unite the global church for the salvation and transformation of nations.  We believe we are living in a New Missions Era.</p>
          <hr class="divider inv">
          <a href="/orphans" class="btn btn-info" data-aos="fade-left">Learn More</a>
        </div><!-- end col -->
      </div><!-- end row -->
    </div><!-- end content-section -->
  </div><!-- end container -->
</div><!-- end white-bg -->
<div class="gray-light-bg">
  <div class="container">
    <div class="content-section">
      <div class="row">
        <div class="col-sm-6 home-half-section">
          <h1 class="dash-trailing" data-aos="fade-right">Angel House</h1>
          <p class="large-type" data-aos="fade-right">Our mission is to bring abandoned children from the slum to safety and into the loving arms of caretakers that will educate, love and raise them to become future leaders in their generation.</p>
          <hr class="divider inv">
          <a href="{{ url('orphans') }}" class="btn btn-info" data-aos="fade-right">Learn More</a>
        </div><!-- end col -->
        <hr class="divider inv lg visible-xs visible-sm">
        <div class="col-sm-6">
          <img data-aos="fade-left" class="img-responsive img-rounded" src="../images/home/angel-house.jpg">
        </div><!-- end col -->
      </div><!-- end row -->
    </div><!-- end content-section -->
  </div><!-- end container -->
</div><!-- end darker-bg -->
<div class="college-vid-bg">
  <video muted autoplay loop poster="images/college/home-placeholder.jpg" class="college-vid-bg-video">
    <source src="images/college/mmc-vid-bg.webm" type="video/webm">
    <source src="images/college/mmc-vid-bg.mp4" type="video/mp4">
    <source src="images/college/mmc-vid-bg.ogv" type="video/ogg">
  </video>
  <div class="container">
    <div class="row">
      <hr class="divider inv xlg">
      <hr class="divider inv xlg">
      <hr class="divider inv xlg">
      <div class="col-md-6 col-md-offset-3 text-center">
        <h1 class="home-college-header text-white" data-aos="fade-up">Missions.Me College</h1>
        <hr class="divider red-small" data-aos="fade-up">
        <hr class="divider inv">
        <p class="text-white" data-aos="fade-up">We believe success is a byproduct of significance and strive to develop a premiere training facility where individuals are equipped with the tools to live impact filled lives.</p>
        <hr class="divider inv">
        <a href="{{ url('college') }}" class="btn btn-white-hollow" data-aos="fade-up">Learn More</a>
        <hr class="divider inv xlg">
        <hr class="divider inv xlg">
        <hr class="divider inv xlg">
        </div>
      </div><!-- end col -->
    </div><!-- end row -->
  </div><!-- end container -->
</div>
<div class="gray-lighter-bg">
  <div class="container">
    <div class="content-section">
      <div class="row">
        <div class="col-sm-12 text-center">
          <h1 data-aos="zoom-in" data-aos-duration="800" data-aos-delay="100">Change The World</h1>
          <a data-aos="zoom-in" data-aos-duration="800" data-aos-delay="200" href="{{ url('login?action=signup') }}" class="btn btn-primary btn-lg">Create Your Account</a>
          <h5 data-aos="zoom-in" data-aos-duration="800" data-aos-delay="300" class="text-uppercase"><a href="{{ url('donate') }}">Or Donate</a></h5>
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