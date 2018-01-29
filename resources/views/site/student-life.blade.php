@extends('site.layouts.default')

@section('title', 'Student Life - Missions.Me College')

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
		    <li><a href="/academics">Academics</a></li>
		    <li class="active"><a href="/student-life">Student Life</a></li>
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
				<li><a href="/college">Home</a></li>
		    	<li><a href="/about-mmc">About</a></li>
		    	<li><a href="/academics">Academics</a></li>
		    	<li class="active"><a href="/student-life">Student Life</a></li>
		    	<li><a href="/admissions">Admissions</a></li>
		    	<li><a href="/admissions#faq">FAQ's</a></li>
		    	<li><a target="_blank" href="https://missionsme.wufoo.com/forms/r1aqae3s12tu8md/">Apply Now</a></li>
			</ul>
		</div>
	</div><!-- end container -->
</div><!-- end white-bg -->
<div class="gray-bg-lighter" style="background:url('/images/college/map-bg.png') bottom no-repeat fixed;">
	<div class="container">
		<div class="content-section">
			<div class="row">
				<div class="col-sm-10 col-md-8 col-sm-offset-1 col-md-offset-2 text-center">
					<h2 class="text-serif" style="margin-top:0;display:inline-block;border-bottom:6px solid #f6323e;line-height:0.7em;">Student Life</h2>
					<p>Student Life is an essential part of the Missions.Me College experience. We want to make sure our students are living each day with purpose and intentionality. There are several aspects to Student Life that help foster a unique community among the students at MMC. Every week you will experience life with fellow MMC students through devotion and worship time, weekly Chapel services and weekly leadership training. The Student Life environment is meant to help you grow spiritually and relationally while sharpening the gifts God has given you. Also with office and student housing only minutes from beautiful beaches, you are more than able to experience the OC culture and all of the perks of living close to LA and San Diego.</p>
				</div>
			</div><!-- end row -->
		</div><!-- end content-section -->
	</div><!-- end container -->
</div>
<hr class="divider" style="margin:0;">
<div class="mmc-cali-bg">
	<div class="container">
		<div class="content-section">
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3 text-center">
					<h2 class="text-serif text-lightweight" style="display:inline-block;border-bottom:6px solid #f6323e;line-height:0.7em;">Location Matters</h2>
				</div>
				<div class="col-sm-4 col-sm-offset-2">
						<h1 class="text-hero text-serif text-lightweight text-primary" style="margin:0 0 15px;line-height:1em;">40</h1>
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
<div class="white-bg">
	<div class="container">
		<div class="content-section">
			<div class="row">
				<div class="col-sm-8 col-sm-offset-2 col-md-5 col-md-offset-0 col-lg-4 col-lg-offset-0">
					<h2 class="text-serif" style="margin-top:0;display:inline-block;border-bottom:6px solid #f6323e;line-height:0.7em;">About OC Living</h2>
				</div>
				<div class="col-sm-8 col-sm-offset-2 col-md-7 col-md-offset-0 col-lg-8 col-lg-offset-0">
					<h4 class="text-serif text-lightweight text-primary">Hike</h4>
					<p class="small">Our office is surrounded by endless miles of walking trails of all intensity levels. At MMC we encourage an active lifestyle and we promise that Southern California will not disappoint.</p>

					<h4 class="text-serif text-lightweight text-primary">Surf</h4>
					<p class="small">No matter your skill level you can surf Salt Creek or many world-famous spots along Southern California’s coastline, our location delivers consistent surf for all skill types.</p>

					<h4 class="text-serif text-lightweight text-primary">Sports</h4>
					<p class="small">Southern California is home to many professional teams and the public parks near the campus welcome you to join a pickup game of basketball, tennis or volleyball a few steps from the ocean.</p>

					<h4 class="text-serif text-lightweight text-primary">Winter Activities</h4>
					<p class="small">If you need a change of pace, let’s say some snow that covers half of the US for 6 months a year, then hop in your car and drive a couple hours to beautiful Lake Tahoe or Big Bear for endless winter sports and activities.</p>

					<h4 class="text-serif text-lightweight text-primary">Theme Parks</h4>
					<h5 class="text-serif text-lightweight">Disneyland:</h5>
					<p class="small">Want to unlock your inner-child let your imagination run free in Disney’s Magic Kingdom. 40 minute drive.</p>

					<h5 class="text-serif text-lightweight">Disney California Adventure:</h5>
					<p class="small">Immerse yourself in a festival of shows, parades and attractions celebrating California's storied past and exciting future. From the gold rush and Hollywood's golden era to the timeless allure of the beach, discover the vast and diverse Golden State.</p>

					<h5 class="text-serif text-lightweight">Sea World:</h5>
					<p class="small">SeaWorld is San Diego’s No. 1 tourist attraction and one of the most popular marine parks in the world. Spread out over more than 189 acres on beautiful Mission Bay Park, SeaWorld is known for its spectacular animal shows, interactive attractions, aquariums, rides and dining facilities.</p>

					<h5 class="text-serif text-lightweight">San Diego Zoo:</h5>
					<p class="small">Located north of downtown San Diego in Balboa Park, home to over 4,000 rare and endangered animals representing more than 800 species and subspecies.</p>


					<h4 class="text-serif text-lightweight text-primary">Shopping</h4>
					<h5 class="text-serif text-lightweight">South Coast Plaza</h5>
					<p class="small">Renowned as a major global destination, South Coast Plaza is “Where the World Comes to Shop”. Home to more than 250 prominent boutiques and critically-acclaimed restaurants South Coast Plaza is adjacent to the celebrated Segerstrom Center for the Arts. Its unparalleled collection of diverse retailers, from international luxury icons like Cartier, Chanel, Chopard, Dior, Dolce & Gabbana, Hermès, and Gucci to famous American brands like Coach, J. Crew, Michael Kors and Tory Burch, along with its customized concierge services, makes it Southern California’s premier shopping experience.</p>

					<h5 class="text-serif text-lightweight">Fashion Island</h5>
					<p class="small">Located in Newport Beach at Pacific Coast Highway and Newport Center Drive, Fashion Island features more than 200 stores and restaurants in a beautiful outdoor garden setting complete with fountains and Koi ponds.  Anchor stores are Bloomingdale’s, Macy’s Women’s Store, Neiman Marcus and Robinsons-May along with specialty shops including Hugo Boss, Kenneth Cole, St. Croix, NIKE Goddess, Greenhouse Spa, and Sharper Image.  401 Newport Center Drive, Newport Beach.</p>

					<h5 class="text-serif text-lightweight">Laguna Beach</h5>
					<p class="small">Originally established as an artist colony, the downtown area of Laguna Beach is wonderful to explore on foot.  Fine art galleries, street-front cafes and unique shops specializing in California casual wear, jewelry, antiques, home furnishings and craft art line the street along Pacific Coast Highway and at the crossroads of Forest and Park Avenues.  Be sure to visit the new “Pottery Place” one-mile south of town and “Gallery Row” located north of the Laguna Art Museum as well as downtown’s “Laguna Village” and “Peppertree Lane.”</p>

					<h5 class="text-serif text-lightweight">Outlets at San Clemente</h5>
					<p class="small">Outlets at San Clemente is Orange County’s first coastal outlet shopping experience where you can discover treasure after treasure amidst a village of Spanish Colonial-style storefronts that beckon you inside to find the perfect brand and the perfect fit.</p>

					<p class="small">To learn more about what else is available near Missions.Me College, check out: <a href="http://www.visittheoc.com">www.visittheoc.com</a></p>
				</div><!-- end col -->
			</div><!-- end row -->
		</div><!-- end content-section -->
	</div><!-- end container -->
</div><!-- end white-bg -->
<hr class="divider" style="margin:0;">
<div class="white-bg">
	<div class="container">
		<div class="content-section">
			<div class="row">
				<div class="col-sm-4 col-md-3">
					<h2 class="text-serif" style="margin-top:0;display:inline-block;border-bottom:6px solid #f6323e;line-height:0.7em;">Spiritual Life</h2>
				</div>
				<div class="col-sm-8 col-md-9">
					<p class="small">Your experience at Missions.Me College involves more than earning a degree. You’ll experience authentic relationships through devotional and worship, one on one mentoring times, Chapel services, exciting student events and more! As part of the Missions.Me family, you will be an important and valued member of our community.</p>
				</div><!-- end col -->
			</div><!-- end row -->
		</div><!-- end content-section -->
	</div><!-- end container -->
</div><!-- end white-bg -->
<div class="gray-lighter-bg">
	<div class="container">
		<div class="content-section">
			<div class="row">
				<div class="col-sm-4 col-md-3">
					<h2 class="text-serif" style="margin-top:0;display:inline-block;border-bottom:6px solid #f6323e;line-height:0.7em;">Housing</h2>
				</div>
				<div class="col-sm-8 col-md-9">
					<p class="small">As part of your tuition, you will be living with other students in our approved student housing complex. Rent and utilities are covered in the cost of your MMC tuition. Students will be living in close proximity to the office and all that OC living has to offer.</p>
				</div><!-- end col -->
			</div><!-- end row -->
		</div><!-- end content-section -->
	</div><!-- end container -->
</div><!-- end white-bg -->
<div class="white-bg">
	<div class="container">
		<div class="content-section">
			<div class="row">
				<div class="col-sm-4 col-md-3">
					<h2 class="text-serif" style="margin-top:0;display:inline-block;border-bottom:6px solid #f6323e;line-height:0.7em;">Missions</h2>
				</div>
				<div class="col-sm-8 col-md-9">
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
<div class="gray-bg-lighter">
	<div class="container">
		<div class="content-section">
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1 text-center">
					<h2 class="text-serif" style="margin-top:0;display:inline-block;border-bottom:6px solid #f6323e;line-height:0.7em;">What Students Say</h2>
					<div class="video-outer">
						<div class="video-inner">
							<iframe src="https://player.vimeo.com/video/130333477?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
						</div>
					</div>
				</div><!-- end col -->
			</div><!-- end row -->
		</div><!-- end content-section -->
	</div><!-- end container -->
</div><!-- end white-bg -->
<hr class="divider" style="margin:0;">
@include('partials.college.questions')
@endsection