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
    $('.scrollNav').easyScroller();
</script>
@endsection

@section('content')
<div class="white-header-bg" style="padding:0;">
	<div class="container">
		<ul class="nav nav-tabs hidden-xs hidden-sm" style="margin-bottom:0;border-bottom:none;">
		    <li class="active"><a href="/college">Home</a></li>
		    <li><a href="/about-mmc">About</a></li>
		    <li><a href="/academics">Academics</a></li>
		    <li><a href="/student-life">Student Life</a></li>
		    <li><a href="/admissions">Admissions</a></li>
		    <li><a href="/mmc-faqs">FAQ's</a></li>
		    <li class="pull-right"><a class="btn btn-info btn-sm" target="_blank" href="https://missionsme.wufoo.com/forms/r1aqae3s12tu8md/">Apply Now</a></li>
		</ul>
		<div class="btn-group btn-group-justified visible-xs-block visible-sm-block" role="group">
			<a class="btn btn-default dropdown-toggle" style="border-radius:0;" data-toggle="dropdown" role="button"
			   aria-haspopup="true" aria-expanded="false">
				Menu <span class="caret"></span>
			</a>
			<ul style="right:0;" class="dropdown-menu">
				<li class="active"><a href="/college">Home</a></li>
		    	<li><a href="/about-mmc">About</a></li>
		    	<li><a href="/academics">Academics</a></li>
		    	<li><a href="/student-life">Student Life</a></li>
		    	<li><a href="/admissions">Admissions</a></li>
		    	<li><a href="/mmc-faqs">FAQ's</a></li>
		    	<li><a target="_blank" href="https://missionsme.wufoo.com/forms/r1aqae3s12tu8md/">Apply Now</a></li>
			</ul>
		</div>
	</div><!-- end container -->
</div><!-- end white-bg -->
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
	    <div class="col-md-12 col-md-offset-0">
	    	<img style="width:200px" src="images/college/mm-college-logo.png" alt="Missions.Me College">
	      <h1 class="text-hero text-white text-uppercase">Live the<br>impossible</h1>
	      <hr class="divider inv">
	      <a target="_blank" href="https://missionsme.wufoo.com/forms/r1aqae3s12tu8md/" class="btn btn-white-hollow btn-lg">Apply Now</a>
	      <hr class="divider inv xlg">
	      <h2 class="text-white text-center" style="margin:0;"><i class="fa fa-angle-down"></i></h2>
	      <hr class="divider inv xlg">
	      <hr class="divider inv xlg">
	      </div>
	    </div><!-- end col -->
	  </div><!-- end row -->
	</div><!-- end container -->
</div>
<div class="gray-bg-lighter">
	<div class="container">
		<div class="content-section" style="padding:0; margin:-60px 0 60px;">
				<div class="row">
					<div class="col-sm-4">
						<div class="panel panel-default">
							<div class="panel-featured" style="position:absolute;left:16px;top:-5px;z-index:999;">
								<h6 class="btn btn-info btn-xs" style="border-radius:50px;"><i class="fa fa-bolt icon-left"></i> Featured</h6>
							</div>
							<a class="launch-modal" data-toggle="modal" data-target="#westVid"><img class="img-responsive" src="/images/college/vid-thumbs/west-campaign.jpg" /></a>
							<div class="panel-body text-center">
								<h6 class="text-uppercase">New Campus Location!</h6>
								<p class="small">We're expanding to Dana Point, CA</p>
								<a href="#" class="btn btn-primary btn-sm">Learn More <i style="margin-left:5px;" class="fa fa-angle-right"></i></a>
							</div>
						</div><!-- end panel -->
					</div><!-- end col -->
					<div class="col-sm-4">
						<div class="panel panel-default">
							<a class="launch-modal" data-toggle="modal" data-target="#seuVid"><img class="img-responsive" src="/images/college/vid-thumbs/seu-partnership.jpg" /></a>
							<div class="panel-body text-center">
								<h6 class="text-uppercase">MMC + SEU</h6>
								<p class="small">Learn about our partnership with SEU.</p>
								<a href="#" class="btn btn-primary btn-sm">Learn More <i style="margin-left:5px;" class="fa fa-angle-right"></i></a>
							</div>
						</div><!-- end panel -->
					</div><!-- end col -->
					<div class="col-sm-4">
						<div class="panel panel-default">
							<a class="launch-modal" data-toggle="modal" data-target="#expVid"><img class="img-responsive" src="/images/college/vid-thumbs/testimonials.jpg" /></a>
							<div class="panel-body text-center">
								<h6 class="text-uppercase">The Experience</h6>
								<p class="small">Hear what former students have to say.</p>
								<a href="#" class="btn btn-primary btn-sm">Student Life <i style="margin-left:5px;" class="fa fa-angle-right"></i></a>
							</div>
						</div><!-- end panel -->
					</div><!-- end col -->
				</div><!-- end row -->
				<hr class="divider inv">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1 text-center">
						<h3>The big dreams in your heart are not an accident.</h3>
						<a class="btn btn-primary" href="/about-mmc">More About Our Program</a>
					</div><!-- end col -->
				</div><!-- end row -->
		</div>
	</div><!-- end container -->
</div><!-- end white-bg -->
<div class="college-cali-bg">
	<div class="container">
		<div class="content-section">
			<div class="row">
				<div class="col-sm-12">
					<h3 class="text-center">Location Matters</h3>
					<hr class="divider red-small">
					<hr class="divider inv lg">
				</div>
				<div class="col-sm-3 text-center">
					<div class="panel panel-default panel-body" style="background-color: rgba(0,0,0,0.3);color:#fff;border:none">
						<h1 class="text-hero">25</h1>
						<h6>Minutes From Disneyland</h6>
					</div>
				</div><!-- end col -->
				<div class="col-sm-3 text-center">
					<div class="panel panel-default panel-body" style="background-color: rgba(0,0,0,0.3);color:#fff;border:none;">
						<h1 class="text-hero">65&deg;</h1>
						<h6>Minutes From Disneyland</h6>
					</div>
				</div><!-- end col -->
				<div class="col-sm-3 text-center">
					<div class="panel panel-default panel-body" style="background-color: rgba(0,0,0,0.3);color:#fff;border:none;">
						<h1 class="text-hero">25</h1>
						<h6>Minutes From Disneyland</h6>
					</div>
				</div><!-- end col -->
				<div class="col-sm-3 text-center">
					<div class="panel panel-default panel-body" style="background-color: rgba(0,0,0,0.3);color:#fff;border:none;">
						<h1 class="text-hero">25</h1>
						<h6>Minutes From Disneyland</h6>
					</div>
				</div><!-- end col -->
			</div><!-- end row -->
		</div><!-- end content-section -->
	</div><!-- end container -->
</div>
<!-- MODAL -->
<div class="modal video-modal fade" id="westVid" tabindex="-1" role="dialog" aria-labelledby="modal-video-label">
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
                        <iframe src="https://player.vimeo.com/video/199504307?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- MODAL -->
<div class="modal video-modal fade" id="seuVid" tabindex="-1" role="dialog" aria-labelledby="modal-video-label">
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
                        <iframe src="https://player.vimeo.com/video/89317516?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- MODAL -->
<div class="modal video-modal fade" id="expVid" tabindex="-1" role="dialog" aria-labelledby="modal-video-label">
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
                        <iframe src="https://player.vimeo.com/video/130333477" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="dark-bg-primary" id="community">
	<div class="container" style="padding:20px 0;">
		<div class="row">
			<div class="col-sm-10 col-sm-offset-1 text-center">
				<h3>Apply today and learn about your next steps. <a style="margin-left:20px;" class="btn btn-white-hollow btn-md" href="#">Apply Now</a></h3>
			</div><!-- end col -->
		</div><!-- end row -->
	</div><!-- end container -->
</div><!-- end gray-light-bg -->
@endsection