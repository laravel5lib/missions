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
<script>
  // init controller
  var controller = new ScrollMagic.Controller({globalSceneOptions: {triggerHook: "onEnter", duration: "250%"}});
  // build scenes
  new ScrollMagic.Scene({triggerElement: "#parallax1"})
          .setTween("#parallax1 > div", {y: "80%", ease: Linear.easeNone})
          .addTo(controller);
</script>
@endsection
@section('content')
<div class="vid-bg">
  <video muted autoplay loop poster="video/video-placeholder.jpg" class="vid-bg-video">
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

<div class="gray-lighter-bg">
    <div class="content-section" style="padding:0;">
      <div class="row" style="margin-left:0;margin-right:0;">
        <div class="col-sm-8 col-sm-offset-0 col-xs-10 col-xs-offset-1 text-center home-vision">
          <h4>See how it all began because of you.<hr class="divider inv visible-sm visible-xs"> <a style="margin-left:10px;" class="btn btn-primary-hollow btn-sm launch-modal" data-toggle="modal" data-target="#video-modal"><i class="fa fa-play"></i></a></h4>
        </div><!-- end col -->
        <div class="col-sm-4 col-sm-offset-0 col-xs-10 col-xs-offset-1 text-center home-social">
          <ul class="social-network-hollow social-circle-hollow">
            <li><a href="https://instagram.com/missionsme" target="_blank" title="Instagram"><i class="fa fa-instagram"></i></a></li>
            <li><a href="https://www.facebook.com/missionsme" target="_blank" title="Facebook"><i class="fa fa-facebook"></i></a></li>
            <li><a href="https://twitter.com/missionsme" target="_blank" title="Twitter"><i class="fa fa-twitter"></i></a></li>
          </ul>
        </div><!-- end col -->
      </div><!-- end row -->
    </div><!-- end content-section -->
  <hr style="margin-top:0;margin-bottom:0;" class="divider xs">
</div><!-- end white-bg -->
<div class="gray-lighter-bg">
  <div class="container">
    <div class="content-section">
      <div class="row">
        <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 text-center">
          <img data-aos="fade-up" class="img-responsive" src="../images/home/take-group.png">
          <h1 data-aos="fade-up">Mobilize A Team</h1>
          <hr data-aos="fade-up" class="divider red-small">
          <hr class="divider inv">
          <p class="large-line-height" data-aos="fade-up">Missions.Me produces the most exciting, highest impact group missions experiences in the world.  Let us provide your team a "once in a life-time” level outreach.</p>
          <hr class="divider inv">
          <a data-aos="fade-up" href="{{ url('groups') }}" class="btn btn-info">Take Your Group</a>
        </div><!-- end col -->
      </div><!-- end row -->
    </div><!-- end content-section -->
  </div><!-- end container -->
</div><!-- end white-bg -->

<div id="parallax1" class="parallaxOnenation">
  <div class="onenation-home-bg">
    <div class="container">
      <div class="onenation-home-section">
        <div class="row">
          <div class="col-sm-6">
            <img data-aos="fade-right" class="img-responsive img-rounded" src="../images/home/1nation1day.jpg">
          </div><!-- end col -->
          <div class="col-sm-6 home-half-section">
            <h1 class="dash-trailing" data-aos="fade-left">1Nation1Day</h1>
            <p class="large-line-height large-type" data-aos="fade-left">We are now living in a new missions era.  The vision of 1Nation1Day is to unite the global church for the salvation and transformation of nations.</p>
            <hr class="divider inv">
            <a href="/1n1d17" class="btn btn-info" data-aos="fade-left">Learn More</a>
          </div><!-- end col -->
        </div><!-- end row -->
      </div><!-- end content-section -->
    </div><!-- end container -->
  </div><!-- end onenation-home-bg -->
</div><!-- end parallax -->
<div class="gray-lighter-bg">
  <div class="container">
    <div class="content-section">
      <div class="row">
        <div class="col-sm-6 home-half-section">
          <h1 class="dash-trailing" data-aos="fade-right">Angel House</h1>
          <p class="large-line-height large-type" data-aos="fade-right">Our mission is to bring abandoned children from the slum to safety and into the loving arms of caretakers that will educate, love and raise them to become future leaders in their generation.</p>
          <hr class="divider inv">
          <a href="{{ url('orphans') }}" class="btn btn-info" data-aos="fade-right">Learn More</a>
        </div><!-- end col -->
        <hr class="divider inv lg visible-xs visible-sm">
        <div class="col-sm-6">
          <img data-aos="fade-up" class="img-responsive img-rounded" src="../images/home/angel-house.jpg">
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
        <p class="large-line-height text-white" data-aos="fade-up">To create a movement of young leaders to <em>Live The Impossible</em> through world-class leadership training, innovative academic instruction, real-world experience, and global outreach opportunities.</p>
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
<div class="dark-bg-primary">
  <div class="container">
    <div class="content-section">
      <div class="row">
        <div class="col-sm-12 text-center">
          <h1 data-aos="zoom-in" data-aos-duration="500" data-aos-delay="100">Begin changing the world with us.</h1>
          <a data-aos="zoom-in" data-aos-duration="500" data-aos-delay="200" href="{{ url('login?action=signup') }}" class="btn btn-default btn-lg" style="background-color:#3e3e3e;border-color:#3e3e3e;">Create Your Account</a>
          <a href="{{ url('donate') }}"><h6 data-aos="zoom-in" data-aos-duration="800" data-aos-delay="300" class="text-uppercase text-white">Or Donate</h6></a>
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