@extends('site.layouts.default')

@section('title', 'Clean Water - Missions.Me')

@section('content')
<div class="content-page-header">
    <img class="img-responsive" src="images/water/water-header.jpg" alt="Speakers">
    <div class="c-page-header-text">
    	<h1 class="text-uppercase dash-trailing">Clean Water</h1>
    </div><!-- end c-page-header-content -->
</div><!-- end c-page-header -->
<div class="white-bg">
	<div class="container">
	<div class="content-section">
		<div class="row">
			<div class="col-sm-6">
				<p class="dash-trailing dash-trailing-info large-type">It kills more people than war. It is the most significant oppressor of women and children in our lifetime. It stunts economic growth and prevents education.</p>
				<hr class="divider inv">
				<h4 class="text-uppercase">What is it?</h4>
				<h1 class="text-uppercase">Dirty,<br>Filthy,<br>Hard-To-Get,</h1>
				<h4 class="text-uppercase text-info"><i class="fa fa-tint"></i> Water.</h4>
			</div>
			<div class="col-sm-6">
				<img class="img-responsive" src="images/water/dirty-water.jpg" alt="Water Crisis">
			</div>
		</div><!-- end row -->
	</div>
	</div><!-- end container -->
</div><!-- end white-bg -->
<div class="content-page-bg">
	<img class="img-responsive" src="images/water/water-crisis-bg.jpg" alt="Water Crisis">
		<div class="c-page-bg-text">
			<div class="col-sm-8">
				<h1 class="text-info dash-trailing dash-trailing-info">The water crisis touches every continent.</h1>
			</div>
		</div><!-- end c-page-header-content -->
</div><!-- end c-page-header -->
<div class="white-bg">
	<div class="container">
	<div class="content-section">
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
				<h3 class="text-center">Our mission is to provide <span class="text-info">clean water</span> to people in need, only a few steps away from their front door.</h3>
				<hr class="divider inv">
				<div class="video-outer">
					<div class="video-inner">
						<iframe src="https://player.vimeo.com/video/142671761" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
					</div>
				</div>
			</div><!-- end col -->
		</div><!-- end row -->
	</div>
	</div>
</div><!-- end white-bg -->
<div class="content-page-bg">
	<img class="img-responsive" src="images/water/well-location-bg.jpg" alt="Well Locations">
		<div class="c-page-bg-text">
			<div class="col-sm-6">
				<h2 class="dash-trailing dash-trailing-info">Project Locations</h2>
				<p>Well project locations are specifically chosen based upon areas prone to drought. Communities will go weeks and months without fresh water causing them to have to travel even farther than usual to obtain water.</p>
			</div>
		</div><!-- end c-page-header-content -->
</div><!-- end c-page-header -->
<div class="white-bg">
	<div class="container">
	<div class="content-section">
		<div class="row">
			<div class="col-xs-12">
				<h1><span class="text-info">Clean Water</span> Provides<br>Community Life Change</h1>
				<hr class="divider xlg">
				<div class="row" style="display:flex; flex-wrap: wrap; flex-direction: row;">
					<div class="col-sm-3 col-xs-6 text-center" style="display:flex">
						<div class="panel panel-default">
							<div class="panel-body">
								<img class="img-md" src="images/water/clock.png" alt="Time">
								<hr class="divider inv">
								<p class="small">From the doorstep to the nearest water source was a 3-hour walk, now it’s 3-minutes.</p>
							</div>
						</div>
					</div>
					<div class="col-sm-3 col-xs-6 text-center" style="display:flex">
						<div class="panel panel-default">
							<div class="panel-body">
								<img class="img-md" src="images/water/tomato.png" alt="Tomato">
								<hr class="divider inv">
								<p class="small">The newfound time allows for more working in the garden, starting a new business, and extra food in the pantry.</p>
							</div>
						</div>
					</div>
					<div class="col-sm-3 col-xs-6 text-center" style="display:flex">
						<div class="panel panel-default">
							<div class="panel-body">
								<img class="img-md" src="images/water/syringe.png" alt="Health">
								<hr class="divider inv">
								<p class="small">Clean water wipes out illness and the village grows because children are surviving past adolescence.</p>
							</div>
						</div>
					</div>
					<div class="col-sm-3 col-xs-6 text-center" style="display:flex">
						<div class="panel panel-default">
							<div class="panel-body">
								<img class="img-md" src="images/water/book.png" alt="Book">
								<hr class="divider inv">
								<p class="small">Kids are able to spend more time in school. They graduate and become teachers, doctors, or business owners.</p>
							</div>
						</div>
					</div>
				</div>
			</div><!-- end col -->
		</div><!-- end row -->
	</div>
	</div>
</div><!-- end white-bg -->
<div class="content-page-bg">
	<img class="img-responsive" src="images/water/guana-well.jpg" alt="Well Locations">
		<div class="c-page-bg-text">
			<div class="col-sm-8 col-sm-offset-2 text-center">
				<h2>A generation will not know what it’s like to drink dirty, filthy, transport-in-a-bucket water ever again.</h2>
				<p><a class="btn btn-info btn-lg" href="/sponsor-a-project">Sponsor A Well</a></p>
				<p><a class="text-white" href="/donate/clean-water-cause">Donate To The Cause</a></p>
			</div>
		</div><!-- end c-page-header-content -->
</div><!-- end c-page-header -->
@endsection