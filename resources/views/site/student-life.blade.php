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
				<div class="col-sm-10 col-md-6 col-sm-offset-1 col-md-offset-3 text-center">
					<h2 class="text-serif" style="margin-top:0;display:inline-block;border-bottom:6px solid #f6323e;line-height:0.7em;">Student Life</h2>
					<p>Nestled in sunny Southern California, the Missions.Me College is an inspirational and disruptive environment that allows students to discover their divine design in beautiful natural surroundings and with friends that also want to change the world. </p>
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
<div class="white-bg">
	<div class="container">
		<div class="content-section">
			<div class="row">
				<div class="col-sm-3 col-md-4">
					<h2 class="text-serif" style="margin-top:0;display:inline-block;border-bottom:6px solid #f6323e;line-height:0.7em;">About Dana Point, CA</h2>
				</div>
				<div class="col-sm-9 col-md-8">
					<h4 class="text-serif text-lightweight text-primary">Hike</h4>
					<p class="small">Dana Point is surrounded by endless miles of walking trails of all intensity levels. At MMC we encourage an active lifestyle and we promise that Southern California will not disappoint.</p>

					<h4 class="text-serif text-lightweight text-primary">Surf</h4>
					<p class="small">No matter your skill level you can surf Salt Creek or many world-famous spots along Southern California’s coastline, our location delivers consistent surf for all skill types.</p>

					<h4 class="text-serif text-lightweight text-primary">Sports</h4>
					<p class="small">Southern California is home to many professional teams and the public parks near the campus welcome you to join a pickup game of basketball, tennis or volleyball a few steps from the ocean.</p>

					<h4 class="text-serif text-lightweight text-primary">Winter Activities</h4>
					<p class="small">If you need a change of pace, let’s say some snow that covers half of the US for 6 months a year, then hop in your car and drive a couple hours to beautiful Lake Tahoe or Big Bear for endless winter sports and activities.</p>

					<h4 class="text-serif text-lightweight text-primary">Theme Parks</h4>
					<p class="small">Disneyland: Want to unlock your inner-child let your imagination run free in Disney’s Magic Kingdom. 40 minute drive.</p>

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
@endsection