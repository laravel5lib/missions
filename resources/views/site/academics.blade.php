@extends('site.layouts.default')

@section('scripts')
<script>
    $('.scrollNav').easyScroller();
</script>
@endsection

@section('content')
<div class="white-header-bg" style="padding:0;">
	<div class="container">
		<ul class="nav nav-tabs hidden-xs hidden-sm" style="margin-bottom:0;border-bottom:none;">
		    <li><a href="/college">Home</a></li>
		    <li><a href="/about-mmc">About</a></li>
		    <li class="active"><a href="/academics">Academics</a></li>
		    <li><a href="/student-life">Student Life</a></li>
		    <li><a href="/admissions">Admissions</a></li>
		    <li><a href="/mmc-faqs">FAQ's</a></li>
		    <li class="pull-right"><a class="btn-college-nav" target="_blank" href="https://missionsme.wufoo.com/forms/r1aqae3s12tu8md/">Apply Now</a></li>
		</ul>
		<div class="btn-group btn-group-justified visible-xs-block visible-sm-block" role="group">
			<a class="btn btn-default dropdown-toggle" style="border-radius:0;" data-toggle="dropdown" role="button"
			   aria-haspopup="true" aria-expanded="false">
				Menu <span class="caret"></span>
			</a>
			<ul style="right:0;" class="dropdown-menu">
				<li><a href="/college">Home</a></li>
		    	<li><a href="/about-mmc">About</a></li>
		    	<li class="active"><a href="/academics">Academics</a></li>
		    	<li><a href="/student-life">Student Life</a></li>
		    	<li><a href="/admissions">Admissions</a></li>
		    	<li class="scrollNav"><a href="/admissions#faq">FAQ's</a></li>
		    	<li><a target="_blank" href="https://missionsme.wufoo.com/forms/r1aqae3s12tu8md/">Apply Now</a></li>
			</ul>
		</div>
	</div><!-- end container -->
</div><!-- end white-bg -->
<div class="gray-bg-lighter" style="background:url('/images/college/map-bg.png') bottom no-repeat fixed;">
	<div class="container">
		<div class="content-section">
			<div class="row">
				<div class="col-sm-3 col-md-2">
					<h2 class="text-serif" style="margin-top:0;display:inline-block;border-bottom:6px solid #f6323e;line-height:0.7em;">Academics</h2>
				</div>
				<div class="col-sm-7 col-md-8">
						<p class="small">MMC is committed to providing students with the best education. Our classroom environment, online learning, and hands on training offer a multi-faceted educational program.</p>

						<p class="small">With a Bachelors of Science and Associate of Arts degree programs accredited under Southeastern University in Florida, students will receive the educational training they need to be successful in whatever field they desire.</p>

						<p class="small">Our hands-on approach is like no other higher learning program in the country. By traveling around the world, working with team members, and serving in ministry, we are committed to equipping this generation of leaders who will influence the culture and guide a life-changing organization.</p>
				</div><!-- end col -->
			</div><!-- end row -->
		</div><!-- end content-section -->
	</div><!-- end container -->
</div>
<hr class="divider" style="margin:0;">
<div class="white-bg">
	<div class="container">
		<div class="content-section">
			<div class="row">
				<div class="col-sm-3 col-md-2">
					<h2 class="text-serif" style="margin-top:0;display:inline-block;border-bottom:6px solid #f6323e;line-height:0.7em;">Missions</h2>
				</div>
				<div class="col-sm-7 col-md-8">
					<p class="small">Missions is the heartbeat of our organization. At MMC you will have the opportunity to go on trips, participate on teams, build missions experiences, and learn how to operate a successful missions organization.</p>

					<p class="small">At Missions.Me, nearly every aspect of the organization is fueled by people like you. Our staff will coach you as we push the limits of traditional missions together.</p>

					<p class="small">We believe in you, and we believe you can change the world. The world of a child born on the streets in India. The world of a single-mother in Ghana who walks three miles everyday for water. The world of a family next door in your neighborhood. Missions isn’t for those called to live in another country their whole lives.</p>
					<a href="/about-mm" class="btn btn-sm btn-primary">About Missions.Me</a>
					<a href="/campaigns" class="btn btn-sm btn-primary">Go On A Trip With Us</a>
				</div><!-- end col -->
			</div><!-- end row -->
		</div><!-- end content-section -->
	</div><!-- end container -->
</div><!-- end white-bg -->
@endsection