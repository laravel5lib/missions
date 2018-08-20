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
<div class="onenation-header">
    <div class="container">
        <div class="row">
          <div class="col-sm-8 col-sm-offset-2 text-center">
            <img width="120px" src="../images/1n1d19/1n1d19-logo-light.png" alt="1Nation1Day Peru">
            <hr class="divider inv lg">
            <img class="img-responsive" src="../images/1n1d19/acw-type.png" alt="All Creation Waits">
            <hr class="divider inv lg">
              <h4 class="text-uppercase">June 20-June 30, 2019</h4>
              <hr class="divider inv xlg">
          </div><!-- end col -->
        </div><!-- end row -->
        <div class="row">
          <div class="col-sm-8 col-sm-offset-2 text-center">
              <div class="video-outer">
                <div class="video-inner">
                  <!-- <div style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/260853749?title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div><script src="https://player.vimeo.com/api/player.js"></script> -->
                  
                  <div style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://www.facebook.com/plugins/video.php?href=https%3A%2F%2Fwww.facebook.com%2Fmissions.me%2Fvideos%2F1876825155671380%2F&show_text=0&width=700" width="700" height="394" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allowFullScreen="true"></iframe></div>

                </div>
              </div>
          </div><!-- end col -->
        </div><!-- end row -->
        <div class="row">
          <hr class="divider inv xlg">
          <div class="col-xs-12 col-sm-8 col-md-3 col-xs-offset-0 col-sm-offset-2 col-md-offset-3 text-center">
              <a href="{{ url('1n1d19') }}" class="btn btn-primary btn-lg btn-block" style="color:#44f0a7!important;margin-bottom:10px;background-color:#fff;border-color:#fff;">Learn More</a>
          </div><!-- end col -->
          <div class="col-xs-12 col-sm-8 col-md-3 col-sm-offset-2 col-md-offset-0 text-center">
              <a href="{{ url('teams') }}" class="btn btn-primary btn-lg btn-block" style="margin-bottom:10px;background-color:#44f0a7;border-color:#44f0a7;">Take a Team</a>
          </div><!-- end col -->
        </div><!-- end row -->
    </div><!-- end container -->
</div><!-- end onenation-header -->
<div class="gray-lighter-bg" style="border-top:4px solid #f6323e;">
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
          <img class="img-responsive" src="../images/home/take-group.png">
          <h1>Mobilize A Team</h1>
          <hr class="divider red-small">
          <hr class="divider inv">
          <p class="large-line-height">Missions.Me produces exciting and impactful team missions experiences.  Let us provide your team a "once in a life-time” level outreach.</p>
          <hr class="divider inv">
          <a href="{{ url('teams') }}" class="btn btn-info">Take Your Team</a>
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
            <img class="img-responsive img-rounded" src="../images/home/1nation1day.jpg">
          </div><!-- end col -->
          <div class="col-sm-6 home-half-section">
            <h1 class="dash-trailing">1Nation1Day</h1>
            <p class="large-line-height large-type">We are living in a new missions era.  The vision of 1Nation1Day is to unite the global church for the salvation and transformation of nations.</p>
            <hr class="divider inv">
            <a href="/1nation1day" class="btn btn-info">Learn More</a>
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
          <h1 class="dash-trailing">Angel House</h1>
          <p class="large-line-height large-type">Life change beings with a home. Angel House has built hundreds of homes and rescued thousands of abandonded children. With your help, we can see 100,000 children rescued in our lifetime!</p>
          <hr class="divider inv">
          <a href="https://angelhouse.me/" class="btn btn-info">Learn More</a>
        </div><!-- end col -->
        <hr class="divider inv lg visible-xs visible-sm">
        <div class="col-sm-6">
          <img class="img-responsive img-rounded" src="../images/home/angel-house.jpg">
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
        <h1 class="home-college-header text-white">Missions.Me College</h1>
        <hr class="divider red-small">
        <hr class="divider inv">
        <p class="large-line-height text-white">Learn to do what others say is impossible. Get an innovative education with world class leadership training. Take part in global outreach opportunities and gain real-world experience!</p>
        <hr class="divider inv">
        <a href="{{ url('college') }}" class="btn btn-white-hollow">Learn More</a>
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
          <h1>Begin changing the world with us.</h1>
          <a href="{{ url('register') }}" class="btn btn-default btn-lg" style="background-color:#3e3e3e;border-color:#3e3e3e;">Get Started</a>
          <a href="{{ url('donate') }}"><h6 class="text-uppercase text-white">Or Donate</h6></a>
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