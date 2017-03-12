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
		    <li><a href="/admissions#faq">FAQ's</a></li>
		    <li class="pull-right"><a class="btn-college-nav" target="_blank" href="https://missionsme.wufoo.com/forms/r1aqae3s12tu8md/">Apply Now</a></li>
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
		    	<li><a href="/admissions#faq">FAQ's</a></li>
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
	    	<img style="width:150px" src="images/college/mm-college-logo.png" alt="Missions.Me College"><br>
	      <h1 class="text-serif text-hero text-white text-lightweight" style="display:inline-block;border-bottom:6px solid #f6323e;line-height:0.8em;margin-bottom:0;">Live The</h1><br>
	      <h1 class="text-serif text-hero text-white text-lightweight" style="display:inline-block;border-bottom:6px solid #f6323e;line-height:0.8em;">Impossible</h1>
	      <hr class="divider inv">
	      <a target="_blank" href="https://missionsme.wufoo.com/forms/r1aqae3s12tu8md/" class="btn btn-white-hollow">Apply Now</a>
	      <hr class="divider inv xlg">
	      <h2 class="text-white text-center" style="margin:0;"><i class="fa fa-angle-down"></i></h2>
	      <hr class="divider inv xlg">
	      <hr class="divider inv xlg">
	      </div>
	    </div><!-- end col -->
	  </div><!-- end row -->
	</div><!-- end container -->
</div>
<div class="gray-bg-lighter" style="background:url('/images/college/map-bg.png') no-repeat bottom fixed;">
	<div class="container">
		<div class="content-section" style="padding:0; margin:-60px 0 60px;">
				<div class="row">
					<div class="col-sm-4">
						<div class="panel panel-default">
							<div class="panel-featured" style="position:absolute;left:16px;top:-5px;z-index:999;">
								<h6 class="btn btn-primary btn-xs" style="border-radius:50px;"><i class="fa fa-bolt icon-left"></i> Featured</h6>
							</div>
							<a class="launch-modal" data-toggle="modal" data-target="#westVid"><img class="img-responsive" src="/images/college/vid-thumbs/west-campaign.jpg" /></a>
							<div class="panel-body text-center">
								<h6 class="text-uppercase">New Campus Location!</h6>
								<p class="small">We're expanding to Dana Point, CA</p>
								<a href="/student-life" class="btn btn-primary btn-sm">Learn More <i style="margin-left:5px;" class="fa fa-angle-right"></i></a>
							</div>
						</div><!-- end panel -->
					</div><!-- end col -->
					<div class="col-sm-4">
						<div class="panel panel-default">
							<a class="launch-modal" data-toggle="modal" data-target="#seuVid"><img class="img-responsive" src="/images/college/vid-thumbs/seu-partnership.jpg" /></a>
							<div class="panel-body text-center">
								<h6 class="text-uppercase">Southeastern University</h6>
								<p class="small">Learn about MMC's partnership with SEU.</p>
								<a href="/academics" class="btn btn-primary btn-sm">Learn More <i style="margin-left:5px;" class="fa fa-angle-right"></i></a>
							</div>
						</div><!-- end panel -->
					</div><!-- end col -->
					<div class="col-sm-4">
						<div class="panel panel-default">
							<a class="launch-modal" data-toggle="modal" data-target="#expVid"><img class="img-responsive" src="/images/college/vid-thumbs/testimonials.jpg" /></a>
							<div class="panel-body text-center">
								<h6 class="text-uppercase">The Experience</h6>
								<p class="small">Hear what former students have to say.</p>
								<a href="/student-life" class="btn btn-primary btn-sm">Student Life <i style="margin-left:5px;" class="fa fa-angle-right"></i></a>
							</div>
						</div><!-- end panel -->
					</div><!-- end col -->
				</div><!-- end row -->
				<hr class="divider inv xlg">
				<hr class="divider inv xlg">
				<div class="row">
					<div class="col-sm-6 col-sm-offset-3 text-center">
						<h1 class="text-serif" style="line-height:1.5em;">The big dreams in your heart <span style="display:inline-block;border-bottom:6px solid #f6323e;line-height:0.7em;">are not</span> an accident.</h1>
						<a><h4><i class="fa fa-angle-down"></i></h4></a>
					</div><!-- end col -->
				</div><!-- end row -->
				<hr class="divider inv xlg">
				<hr class="divider inv xlg">
		</div><!-- end content-section -->
	</div><!-- end container -->
</div><!-- end white-bg -->
<hr class="divider" style="margin:0;">
<div class="white-bg">
	<div class="container">
		<div class="content-section">
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3 text-center">
					<h2 class="text-serif" style="display:inline-block;border-bottom:6px solid #f6323e;line-height:0.7em;">The Vision</h2>
					<p>To create a movement of young leaders to <span class="text-primary text-serif"><em>Live The Impossible</em></span> through world-class leadership training, innovative academic instruction, real-world experience, and global outreach opportunities.</p>
				</div><!-- end col -->
			</div><!-- end row -->
		</div><!-- end content-section -->
	</div><!-- end container -->
</div><!-- end white-bg -->
<div class="mmc-cali-bg">
	<div class="container">
		<div class="content-section">
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3 text-center">
					<h2 class="text-serif text-lightweight" style="display:inline-block;border-bottom:6px solid #f6323e;line-height:0.7em;">Location Matters</h2>
                    <p>The Missions.Me College will be kicking off it's Fall 2017 semester
					{{-- <p>Nestled --}} in sunny Southern California, {{-- the Missions.Me College is --}} providing an inspirational and disruptive environment that allows students to discover their divine design in beautiful natural surroundings and with friends that also want to change the world.</p>
				</div>
				<div class="col-sm-4 col-sm-offset-2">
						<h1 class="text-hero text-serif text-lightweight text-primary" style="margin:0 0 15px;line-height:1em;">25</h1>
						<p class="small">Minutes From Disneyland <i class="fa fa-angle-right pull-right" style="opacity:0.5;"></i></p>
						<hr class="divider" style="opacity:0.3;">
						<h1 class="text-hero text-serif text-lightweight text-primary" style="margin:0 0 15px;line-height:1em;">70&deg;</h1>
						<p class="small">Average Annual Temperature <i class="fa fa-angle-right pull-right" style="opacity:0.5;"></i></p>
						<hr class="divider" style="opacity:0.3;">
						<h1 class="text-hero text-serif text-lightweight text-primary" style="margin:0 0 15px;line-height:1em;">225</h1>
						<p class="small">Days Of Sunshine A Year <i class="fa fa-angle-right pull-right" style="opacity:0.5;"></i></p>
						<hr class="divider" style="opacity:0.3;">
						<h1 class="text-hero text-serif text-lightweight text-primary" style="margin:0 0 15px;line-height:1em;">30</h1>
						<p class="small">Seconds From The Beach <i class="fa fa-angle-right pull-right" style="opacity:0.5;"></i></p>
						<hr class="divider" style="opacity:0.3;">
				</div><!-- end col -->
				<div class="col-sm-4">
					<hr class="divider inv">
					<img class="img-responsive img-rounded" src="/images/college/dana-point.jpg" />
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
                        <iframe src="https://player.vimeo.com/video/200239351?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
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
<div class="dark-bg-primary">
	<div class="container" style="padding:20px 0;">
			<div class="col-xs-12 col-xs-offset-0 col-sm-10 col-sm-offset-1 text-center">
				<h3 class="hidden-xs">Apply today and learn about your next steps. <a style="margin-left:20px;" class="btn btn-white-hollow btn-md" target="_blank" href="https://missionsme.wufoo.com/forms/r1aqae3s12tu8md/">Apply Now</a></h3>
				<div class="visible-xs">
					<h3>Apply today and learn about your next steps.</h3>
					<a class="btn btn-white-hollow btn-md" target="_blank" href="https://missionsme.wufoo.com/forms/r1aqae3s12tu8md/">Apply Now</a>
				</div>
			</div><!-- end col -->
	</div><!-- end container -->
</div><!-- end gray-light-bg -->
@endsection