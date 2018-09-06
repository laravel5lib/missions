@extends('site.layouts.default')

@section('title', 'Coordinators - Missions.Me')

@section('scripts')
<script>
$('.launch-modal').on('click', function(e){
    e.preventDefault();
    $( '#' + $(this).data('gform-modal') ).modal();
});
</script>
@endsection

@section('content')
<div class="content-page-header">
    <img class="img-responsive" src="images/coordinators/coordinators-header.jpg" alt="Coordinators">
    <div class="c-page-header-text">
    	<h1 class="text-uppercase dash-trailing">1N1D 2019 Coordinators</h1>
    </div><!-- end c-page-header-content -->
</div><!-- end c-page-header -->
    <div class="gray-bg-lighter" style="background:url('/images/college/map-bg.png') no-repeat bottom fixed;">
      <div class="container">
        <div class="content-section">
          <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-xs-12 col-xs-offset-0 text-center">
              <h6 class="text-uppercase">Welcome to the Team</h6>
              <h2>We're Here To Help You!</h2>
              <hr class="divider red-small xlg">
              <div class="video-outer">
                <div class="video-inner">
                  <iframe src="https://player.vimeo.com/video/263068741?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                </div><!-- end video-inner -->
              </div><!-- end video-outer -->
            </div><!-- end col -->
          </div><!-- end row -->
        </div><!-- end content-section -->
      </div><!-- end container -->
    </div><!-- end gray-lighter-bg -->
    <div class="dark-bg-primary" style="padding:30px 0 50px;">
      <div class="container">
          <div class="row">
            <div class="col-xs-12 text-center">
              <h5>Complete this Team Launch form</h5>
              <a class="btn btn-white-hollow btn-lg" data-toggle="modal" data-target="#gform-modal">Team Launch Form</a>
            </div><!-- end col -->
          </div><!-- end row -->
      </div><!-- end container -->
    </div><!-- end gray-lighter-bg -->
    <div class="gray-lighter-bg">
      <div class="container">
        <div class="content-section">
          <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-xs-12 col-xs-offset-0 text-center">
                <h2>Launch Your Team</h2>
                <h5>Everything you need for an incredible team launch!</h5>
                <hr class="divider red-small xlg">
            </div><!-- end col -->
          </div><!-- end row -->
          <div class="row">
            <div class="col-sm-6 col-sm-offset-3 col-xs-12 col-xs-offset-0">
              <div class="video-outer">
                <div class="video-inner">
                  <iframe src="https://player.vimeo.com/video/263405694?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                </div><!-- end video-inner -->
              </div><!-- end video-outer -->
            </div>
          </div>
          <hr class="divider inv xlg">
          <div class="row">
            <div class="col-sm-12 col-sm-offset-0">
              <div class="row">
                <div class="col-sm-6 col-md-3 col-xs-12">
                  <div class="panel panel-default" style="border-style:solid;border-width:4px 0 0;border-color:#f6323e;">
                    <div class="panel-body text-center">
                      <hr class="divider inv">
                      <img src="/images/coordinators/docs-icon.png" width="50px" height="50px">
                      <h6 class="text-uppercase">Launch Media</h6>
                      <p class="small">Including logos, graphics, posters, signage and more</p>
                      <a href="https://www.dropbox.com/s/f9ughy785agop7j/1N1D19-promo-pack.zip?dl=1" class="btn btn-sm btn-primary-hollow">Download</a>
                      <hr class="divider inv">
                    </div>
                  </div>
                </div><!-- end col -->
                <div class="col-sm-6 col-md-3 col-xs-12">
                  <div class="panel panel-default" style="border-style:solid;border-width:4px 0 0;border-color:#f6323e;">
                    <div class="panel-body text-center">
                      <hr class="divider inv">
                      <img src="/images/coordinators/vid-icon.png" width="50px" height="50px">
                      <h6 class="text-uppercase">Promo Video</h6>
                      <p class="small">Great for assisting in delivering the full vision on your Launch Day</p>
                      <a href="https://www.dropbox.com/s/hasb44papw6kz3l/1N1D19-Full-Promo.zip?dl=1" class="btn btn-sm btn-primary-hollow">Download</a>
                      <hr class="divider inv">
                    </div>
                  </div>
                </div><!-- end col -->
                <div class="col-sm-6 col-md-3 col-xs-12">
                  <div class="panel panel-default" style="border-style:solid;border-width:4px 0 0;border-color:#f6323e;">
                    <div class="panel-body text-center">
                      <hr class="divider inv">
                      <img src="/images/coordinators/vid-icon.png" width="50px" height="50px">
                      <h6 class="text-uppercase">Teaser Video</h6>
                      <p class="small">Great for announcements in the weeks prior to launch</p>
                      <a href="https://www.dropbox.com/s/pgetlm50v4kiw56/1N1D19-Teaser.zip?dl=1" class="btn btn-sm btn-primary-hollow">Download</a>
                      <hr class="divider inv">
                    </div>
                  </div>
                </div><!-- end col -->
                <div class="col-sm-6 col-md-3 col-xs-12">
                  <div class="panel panel-default" style="border-style:solid;border-width:4px 0 0;border-color:#f6323e;">
                    <div class="panel-body text-center">
                      <hr class="divider inv">
                      <img src="/images/coordinators/vid-icon.png" width="50px" height="50px">
                      <h6 class="text-uppercase">Short Trailer</h6>
                      <p class="small">Great for announcements in the weeks following launch</p>
                      <a href="https://www.dropbox.com/s/0ayszte3zexos9i/1N1D19-Short-Trailer.zip?dl=1" class="btn btn-sm btn-primary-hollow">Download</a>
                      <hr class="divider inv">
                    </div>
                  </div>
                </div><!-- end col -->
              </div><!-- end row -->
              <div class="row">
                <div class="col-sm-6 col-md-3 col-xs-12">
                  <div class="panel panel-default" style="border-style:solid;border-width:4px 0 0;border-color:#f6323e;">
                    <div class="panel-body text-center">
                      <hr class="divider inv">
                      <img src="/images/coordinators/cam-icon.png" width="50px" height="50px">
                      <h6 class="text-uppercase">Launch Capture</h6>
                      <p class="small">Instructions for your media team to capture your launch</p>
                      <a href="https://www.dropbox.com/s/swt4hp5ib409dg4/1N1D19-Launch-Media-Guide.pdf?dl=1" class="btn btn-sm btn-primary-hollow">Download</a>
                      <hr class="divider inv">
                    </div>
                  </div>
                </div><!-- end col -->
                <div class="col-sm-6 col-md-3 col-xs-12">
                  <div class="panel panel-default" style="border-style:solid;border-width:4px 0 0;border-color:#f6323e;">
                    <div class="panel-body text-center">
                      <hr class="divider inv">
                      <img src="/images/coordinators/vision-doc-icon.png" width="50px" height="50px">
                      <h6 class="text-uppercase">Vision Booklet</h6>
                      <p class="small">Print ready booklet file ready to submit to your printer</p>
                      <a href="{{ download_file('resources/coordinators/vision-packet-v2_final.pdf.zip') }}" class="btn btn-sm btn-primary-hollow">Download</a>
                      <hr class="divider inv">
                    </div>
                  </div>
                </div><!-- end col -->
                <div class="col-sm-6 col-md-3 col-xs-12">
                  <div class="panel panel-default" style="border-style:solid;border-width:4px 0 0;border-color:#f6323e;">
                    <div class="panel-body text-center">
                      <hr class="divider inv">
                      <img src="/images/coordinators/docs-icon.png" width="50px" height="50px">
                      <h6 class="text-uppercase">Sample Schedule</h6>
                      <p class="small">What a typical week of ministry will look like in country.</p>
                      <a href="{{ download_file('resources/coordinators/2019_schedule.pdf') }}" class="btn btn-sm btn-primary-hollow">Download</a>
                      <hr class="divider inv">
                    </div>
                  </div>
                </div><!-- end col -->
                <div class="col-sm-6 col-md-3 col-xs-12">
                  <div class="panel panel-default" style="border-style:solid;border-width:4px 0 0;border-color:#f6323e;">
                    <div class="panel-body text-center">
                      <hr class="divider inv">
                      <img src="/images/coordinators/docs-icon.png" width="50px" height="50px">
                      <h6 class="text-uppercase">Role Brochures</h6>
                      <p class="small">All the role brochures in PDF format ready for print.</p>
                      <a href="https://www.dropbox.com/s/iqq0icdktbyxtr3/role-brochures-dwnld.zip?dl=0" class="btn btn-sm btn-primary-hollow">Download</a>
                      <hr class="divider inv">
                    </div>
                  </div>
                </div><!-- end col -->
              </div><!-- end row -->
            </div><!-- end col -->
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="gray-lighter-bg">
      <div class="container">
        <div class="content-section">
          <div class="row">
            <div class="col-xs-10 col-xs-offset-1">
              <h2 class="text-center">Monthly Campaign Updates</h2>
              <hr class="divider red-small xlg">
            </div>
          </div>
        </div>
      </div>
    </div> -->
    <div class="gray-light-bg">
      <div class="container">
        <div class="content-section">
          <div class="row">
            <div class="col-xs-10 col-xs-offset-1">
              <h2 class="text-center">Monthly Coordinator Calls</h2>
              <hr class="divider red-small xlg">
              <div class="row">
                <div class="col-xs-8 col-xs-offset-2">
                  <p class="text-center">Each month 1Nation1Day Coordinators from around the world will join together as one global team.  Led by the Missions.Me team, you’ll get updates on the campaign, new media to share with your team, opportunities to answer questions live and exchange ideas with other coordinators.</p> 
                </div><!-- end col -->
              </div><!-- end row -->
              <hr class="divider inv lg">
              <div class="panel panel-default">
                <div class="panel-heading text-center" style="background:#2073d9;padding:40px;">
                  <h4 class="text-white" style="display:inline-block;margin-right:10px;">Connect to the Video Conference on meeting dates</h4> <a class="btn btn-white-hollow btn-md" href="https://zoom.us/j/582208588"><i class="fa fa-play icon-left"></i> Connect</a>
                </div><!-- end panel-title -->
                <div class="panel-body" style="padding:40px;">
                  <h6 class="text-uppercase text-center">Video Conference Call Dates</h6>
                    <hr class="divider lg" style="width:50px;">
                <div class="row">
                  <div class="col-md-4 col-md-offset-0 col-sm-12 col-sm-offset-0">
                    <ul class="list-unstyled">
                      <li>
                        <img src="/images/coordinators/chat-icon.png" style="margin-right:5px;" width="25px" height="25px"> 
                        <h6 style="display:inline-block;margin-bottom:0;" class="text-muted">May 23 at 4pm EST</h6>
                        <p class="text-uppercase" style="font-size:10px;margin-left:35px;">
                          <a href="https://vimeo.com/271571861/8270ebaa44" target="_blank">Watch Meeting</a>
                        </p>
                      </li>
                      <li>
                        <img src="/images/coordinators/chat-icon.png" style="margin-right:5px;" width="25px" height="25px"> 
                        <h6 style="display:inline-block;" class="text-muted">June 27 at 4pm EST</h6>
                        <p class="text-uppercase" style="font-size:10px;margin-left:35px;">
                          <a href="https://vimeo.com/277366501/6c06a358b3" target="_blank">Watch Meeting</a>
                        </p>
                      </li>
                      <li><img src="/images/coordinators/chat-icon.png" style="margin-right:5px;" width="25px" height="25px"> <h6 class="text-muted" style="display:inline-block;">No meeting in July</h6></li>
                      <li>
                        <img src="/images/coordinators/chat-icon.png" style="margin-right:5px;" width="25px" height="25px"> 
                        <h6 style="display:inline-block;" class="text-muted">August 15 at 4pm EST</h6>
                        <p class="text-uppercase" style="font-size:10px;margin-left:35px;">
                          <a href="https://vimeo.com/285186197/80c88c79b3" target="_blank">Watch Meeting</a>
                        </p>
                      </li>
                      <li><img src="/images/coordinators/chat-icon.png" style="margin-right:5px;" width="25px" height="25px"> <h6 style="display:inline-block;">September 19 at 4pm EST</h6></li>
                    </ul>
                  </div>
                  <div class="col-md-4 col-md-offset-0 col-sm-12 col-sm-offset-0">
                    <ul class="list-unstyled">
                      <li><img src="/images/coordinators/chat-icon.png" style="margin-right:5px;" width="25px" height="25px"> <h6 style="display:inline-block;">October 17 at 4pm EST</h6></li>
                      <li><img src="/images/coordinators/chat-icon.png" style="margin-right:5px;" width="25px" height="25px"> <h6 style="display:inline-block;">November 14 at 4pm EST</h6></li>
                      <li><img src="/images/coordinators/chat-icon.png" style="margin-right:5px;" width="25px" height="25px"> <h6 style="display:inline-block;">December 12 at 4pm EST</h6></li>
                      <li><img src="/images/coordinators/chat-icon.png" style="margin-right:5px;" width="25px" height="25px"> <h6 style="display:inline-block;">January 9 at 4pm EST</h6></li>
                      <li><img src="/images/coordinators/chat-icon.png" style="margin-right:5px;" width="25px" height="25px"> <h6 style="display:inline-block;">February 20 at 4pm EST</h6></li>
                    </ul>
                  </div>
                  <div class="col-md-4 col-md-offset-0 col-sm-12 col-sm-offset-0">
                    <ul class="list-unstyled">
                      <li><img src="/images/coordinators/chat-icon.png" style="margin-right:5px;" width="25px" height="25px"> <h6 style="display:inline-block;">March 20 at 4pm EST</h6></li>
                      <li><img src="/images/coordinators/chat-icon.png" style="margin-right:5px;" width="25px" height="25px"> <h6 style="display:inline-block;">April 17 at 4pm EST</h6></li>
                      <li><img src="/images/coordinators/chat-icon.png" style="margin-right:5px;" width="25px" height="25px"> <h6 style="display:inline-block;">May 15 at 4pm EST</h6></li>
                      <li><img src="/images/coordinators/chat-icon.png" style="margin-right:5px;" width="25px" height="25px"> <h6 style="display:inline-block;">June 5 at 4pm EST</h6></li>
                    </ul>
                  </div>
                </div><!-- end row -->
                <p class="small text-italic">* Note, you’ll need to download the FREE Zoom application on a computer or mobile device to connect to the meeting. Please have this installed before tuning into your first meeting.</p>
                </div><!-- end panel-body -->
              </div><!-- end panel -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="gray-lighter-bg" style="background:url('/images/college/map-bg.png') no-repeat bottom fixed;">
      <div class="container">
        <div class="content-section">
          <div class="row">
            <div class="col-xs-10 col-xs-offset-1">
              <h2 class="text-center">Who We Are</h2>
              <hr class="divider red-small xlg">
              <div class="row">
                <div class="col-md-3 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                  <div class="panel">
                    <a href="http://stripe.com" target="_blank">
                      <img class="img-responsive" src="images/coordinators/matt.jpg" alt="">
                    </a>
                    <div class="panel-body">
                      <h5>Matt Sheehan</h5>
                      <p style="margin-bottom:5px;" class="small">Director Of Operations</p>
                      <p style="margin-bottom:0;"><a href="http://instagram.com/_sheehan"><i style="vertical-align:middle;" class="fa fa-instagram"></i></a></p>
                    </div>
                  </div>
                </div><!-- end col -->
                <div class="col-md-3 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                  <div class="panel">
                      <img class="img-responsive" src="images/coordinators/jontel.jpg" alt="">
                    <div class="panel-body">
                      <h5>Jontel Lidman</h5>
                      <p style="margin-bottom:5px;" class="small">Trip Operations</p>
                      <p style="margin-bottom:0;"><a href="http://instagram.com/jontel08"><i style="vertical-align:middle;" class="fa fa-instagram"></i></a></p>
                    </div>
                  </div>
                </div><!-- end col -->
                <div class="col-md-3 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                  <div class="panel panel-default">
                      <img class="img-responsive" src="images/coordinators/lib.jpg" alt="">
                    <div class="panel-body">
                      <h5>Libby Gonzales</h5>
                      <p style="margin-bottom:5px;" class="small">Team Relations</p>
                      <p style="margin-bottom:0;"><a href="http://instagram.com/Libbygonzales"><i style="vertical-align:middle;" class="fa fa-instagram"></i></a></p>
                    </div>
                  </div>
                </div><!-- end col -->
                <div class="col-md-3 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                  <div class="panel">
                      <img class="img-responsive" src="images/coordinators/jackie.jpg" alt="">
                    <div class="panel-body">
                      <h5>Jackie Rios</h5>
                      <p style="margin-bottom:5px;" class="small">Accounts</p>
                      <p style="margin-bottom:0;"><a href="http://instagram.com/jackiechackie"><i style="vertical-align:middle;" class="fa fa-instagram"></i></a></p>
                    </div><!-- end panel-body -->
                  </div><!-- end panel -->
                </div><!-- end col -->
              </div><!-- end row -->
            </div><!-- end col -->
          </div><!-- end row -->
        </div><!-- end content-section -->
      </div><!-- end container -->
    </div><!-- end gray-lighter-bg -->
    <div class="gray-light-bg">
      <div class="container">
        <div class="content-section">
          <div class="row">
            <div class="col-sm-6 col-sm-offset-0 col-xs-12 text-center">
              <div class="panel panel-default">
                <div class="panel-body text-center" style="padding:40px;">
                  <img src="images/coordinators/env-icon.png" width="50px" height="50px">
                  <h5 class="text-uppercase">Questions Or Comments?</h5>
                  <a class="btn btn-primary btn-md" href="mailto:coordinators@missions.me">Email Us</a>
                  <hr class="divider inv sm">
                  <p class="small"><a href="mailto:coordinators@missions.me">coordinators@missions.me</a></p>
                </div><!-- end panel-body -->
              </div><!-- end panel -->
            </div><!-- end col -->
            <div class="col-sm-6 col-sm-offset-0 col-xs-12 text-center">
              <div class="panel panel-default">
                <div class="panel-body text-center" style="padding:40px;">
                  <img src="images/coordinators/env-icon.png" width="50px" height="50px">
                  <h5 class="text-uppercase">Questions about missionary accounts?</h5>
                  <a class="btn btn-primary btn-md" href="mailto:accounts@missions.me">Email Us</a>
                  <hr class="divider inv sm">
                  <p class="small"><a href="mailto:accounts@missions.me">accounts@missions.me</a></p>
                </div><!-- end panel-body -->
              </div><!-- end panel -->
            </div><!-- end col -->
          </div><!-- end row -->
        </div><!-- end content-section -->
      </div><!-- end container -->
    </div><!-- end gray-lighter-bg -->

<!-- MODAL -->
<div class="modal fade" id="gform-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">1N1D 2019 Team Launch Form</h4>
            </div>
            <div class="modal-body text-center">
                <iframe src="https://docs.google.com/forms/d/e/1FAIpQLSdQw1z2ZvpuIAW4Xm4gsU35s7tue8-cMbPANrvs-t6386syAA/viewform?embedded=true" width="850" height="500" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe>
            </div>
        </div>
    </div>
</div>
@endsection