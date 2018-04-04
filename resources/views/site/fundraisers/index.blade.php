@extends('site.layouts.default')

@section('content')
    <fundraisers></fundraisers>

	<hr class="divider" style="margin-top:0;margin-bottom:0;">

	@component('carousel', [
		'id' => 'carousel-fundraising-platform',
		'class' => 'hidden-sm hidden-xs',
		'indicators' => true,
		'controls' => true
	])
	  	<div class="item active">
	        <img class="img-responsive" src="../images/fundraising/fund-slide-1.jpg">
	        <div class="carousel-caption">
	        	<h3 class="dash-trailing">Customize Your Fundraising Page</h3>
	            <p>Fundraising pages are easily customizable with your personalized message, stories, photos, and videos to help you reach your goal.</p>
	        </div>
	    </div><!-- end item -->
		<div class="item">
	        <img class="img-responsive" src="../images/fundraising/fund-slide-2.jpg">
	        <div class="carousel-caption">
	        	<h3 class="dash-trailing">Share A Story</h3>
	            <p>Keep your friends and family informed with the Stories feature that allows you to share your journey with supporters along the way. Post a story and share your page on your favorite social media platform.</p>
	        </div>
	    </div><!-- end item -->
	    <div class="item">
	        <img class="img-responsive" src="../images/fundraising/fund-slide-3.jpg">
	        <div class="carousel-caption">
	        	<h3 class="dash-trailing">Receive Donations</h3>
	            <p>Receive donations through a simple, fast, and secure button right on your Fundraising page. No need to be directed to a clunky third party.</p>
	        </div>
	    </div><!-- end item -->
	    <div class="item">
	        <img class="img-responsive" src="../images/fundraising/fund-slide-4.jpg">
	        <div class="carousel-caption">
	        	<h3 class="dash-trailing">View Donors & Donation Amounts</h3>
	            <p>It’s fun to see who’s supporting you. View each donor by name as well as the amount donated to your cause.</p>
	        </div>
	    </div><!-- end item -->
	    <div class="item">
	        <img class="img-responsive" src="../images/fundraising/fund-slide-5.jpg">
	        <div class="carousel-caption">
	        	<h3 class="dash-trailing">Track Progress</h3>
	            <p>View your progress by dollar amount raised, percent, and how much you have to go until you’ve reached your goal. Fundrasing made easy and fun!</p>
	        </div>
	    </div><!-- end item -->
	@endcomponent

	<hr class="divider hidden-xs hidden-sm" style="margin-top:0;margin-bottom:0;">

	@component('section', ['class' => 'white-bg visible-sm visible-xs'])
		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<img class="img-responsive" src="../images/fundraising/mobile/fund-slide-1.png">
			</div><!-- end col -->
			<div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-0">
				<h3 class="dash-trailing">Customize Your Fundraising Page</h3>
	            <p>Fundraising pages are easily customizable with your personalized message, stories, photos, and videos to help you reach your goal.</p>
	        </div><!-- end col -->
	    </div><!-- end row -->
	@endcomponent

	@component('section', ['class' => 'gray-lighter-bg visible-sm visible-xs'])
		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<img class="img-responsive" src="../images/fundraising/mobile/fund-slide-2.png">
			</div><!-- end col -->
			<div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-0">
				<h3 class="dash-trailing">Share A Story</h3>
	            <p>Keep your friends and family informed with the Stories feature that allows you to share your journey with supporters along the way. Post a story and share your page on your favorite social media platform.</p>
	        </div><!-- end col -->
	    </div><!-- end row -->
	@endcomponent

	@component('section', ['class' => 'white-bg visible-sm visible-xs'])
		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<img class="img-responsive" src="../images/fundraising/mobile/fund-slide-3.png">
			</div><!-- end col -->
			<div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-0">
				<h3 class="dash-trailing">Receive Donations</h3>
	            <p>Receive donations through a simple, fast, and secure button right on your Fundraising page. No need to be directed to a clunky third party.</p>
	        </div><!-- end col -->
	    </div><!-- end row -->
	@endcomponent

	@component('section', ['class' => 'gray-lighter-bg visible-sm visible-xs'])
		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<img class="img-responsive" src="../images/fundraising/mobile/fund-slide-4.png">
			</div><!-- end col -->
			<div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-0">
				<h3 class="dash-trailing">View Donors & Donation Amounts</h3>
	            <p>It’s fun to see who’s supporting you. View each donor by name as well as the amount donated to your cause.</p>
	        </div><!-- end col -->
	    </div><!-- end row -->
	@endcomponent

	@component('section', ['class' => 'white-bg visible-sm visible-xs'])
		<div class="row">
			<div class="col-xs-12 col-sm-6">
	    		<img class="img-responsive" src="../images/fundraising/mobile/fund-slide-5.png">
	    	</div><!-- end col -->
			<div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-0">
				<h3 class="dash-trailing">Track Progress</h3>
	            <p>View your progress by dollar amount raised, percent, and how much you have to go until you’ve reached your goal. Fundrasing made easy and fun!</p>
	        </div><!-- end col -->
	    </div><!-- end row -->
	@endcomponent

	@component('section', ['class' => 'gray-lighter-bg'])
		<div class="row" id="ideas">
			<div class="col-sm-12 col-sm-offset-0 col-xs-10 col-xs-offset-1">
				<h2 class="text-center">Fundraising made easy and fun!</h2>
			</div><!-- end col -->
		</div><!-- end row -->
	@endcomponent

	@component('section', ['class' => 'dark-bg-primary'])
		<div class="row">
			<div class="col-sm-12 col-sm-offset-0 col-xs-10 col-xs-offset-1">
	        	<h1 class="text-center" id="fundraising">Top 4 Ways People Reach Their Goal</h1>
	        	<p class="text-primary-darker text-center">Fundraising doesn't have to be a scary task.<br>Have fun with it!</p>
	        	<hr class="divider inv lg">
			    <div class="row" style="display:flex; flex-wrap: wrap; flex-direction: row;">
		        	<div class="col-sm-3" style="display:flex">
			        	<div class="panel panel-default">
			        		<img class="img-responsive" src="images/fundraising/social-media.jpg" alt="">
							<div class="panel-body">
								<h5>Share Your Profile</h5>
								<p class="small">Tell people you’re going on a trip to change the world and ask people to help donate online by sharing your Missions.Me profile on social media!</p>
							</div>
			        	</div><!-- end panel -->
			        </div><!-- end col -->
			        <div class="col-sm-3" style="display:flex">
			        	<div class="panel panel-default">
			        		<img class="img-responsive" src="images/fundraising/phone-call.jpg" alt="">
							<div class="panel-body">
								<h5>Support Letters & Calls</h5>
								<p class="small">Good ol’ fashion letters sent via email or snail mail to your family and friends, you can even text them!</p>
							</div>
			        	</div><!-- end panel -->
			        </div><!-- end col -->
			        <div class="col-sm-3" style="display:flex">
			        	<div class="panel panel-default">
			        		<img class="img-responsive" src="images/fundraising/letter.jpg" alt="">
							<div class="panel-body">
								<h5>Envelope Fundraiser</h5>
								<p class="small">Get 50 Envelopes and label them in dollar amounts from $1 to $50 and then ask someone to pick an amount on the envelope to donate. If you get all 50 envelopes back, you raise $1,275.</p>
							</div>
			        	</div><!-- end panel -->
			        </div><!-- end col -->
			        <div class="col-sm-3" style="display:flex">
			        	<div class="panel panel-default">
			        		<img class="img-responsive" src="images/fundraising/cook.jpg" alt="">
							<div class="panel-body">
								<h5>Throw A Party Or Cook A Meal</h5>
								<p class="small">Get some of your friends together and throw an 80’s party, invite everyone, and charge admission. OR, Cook a meal and have people donate in order to pay for their food.</p>
							</div>
			        	</div><!-- end panel -->
			        </div><!-- end col -->
			    </div><!-- end row -->
	        </div><!-- end col -->
		</div><!-- end row -->
	@endcomponent

	@component('section', ['class' => 'darker-bg-primary'])
		<div class="row">
			<div class="col-sm-12 col-sm-offset-0 col-xs-10 col-xs-offset-1">
	        	<h2 class="text-center">Fundraising Downloads</h2>
	        	<hr class="divider inv lg">
			    <div class="row">
		        	<div class="col-md-3 col-sm-6">
						<a class="btn btn-info btn-block" href="downloads/FundraisingGuide.pdf" target="_blank">Fundraising Guide</a>
						<hr class="divider inv hidden-md">
			        </div><!-- end col -->
			        <div class="col-md-3 col-sm-6">
						<a class="btn btn-info btn-block" href="downloads/100fundraising.pdf" target="_blank">100 Fundraising Ideas</a>
						<hr class="divider inv hidden-md">
			        </div><!-- end col -->
			        <div class="col-md-3 col-sm-6">
						<a class="btn btn-info btn-block" href="downloads/SampleLetter.pdf" target="_blank">Sample Support Letter</a>
						<hr class="divider inv hidden-md">
			        </div><!-- end col -->
			        <div class="col-md-3 col-sm-6">
						<a class="btn btn-info btn-block" href="downloads/ParentsLetter.pdf" target="_blank">Parents Letter</a>
						<hr class="divider inv hidden-md">
			        </div><!-- end col -->
			    </div><!-- end row -->
	        </div><!-- end col -->
		</div><!-- end row -->
	@endcomponent

	@component('section', ['class' => 'lighter-gray-bg'])
		<div class="row">
			<div class="col-sm-12 col-sm-offset-0 col-xs-10 col-xs-offset-1">
	        	<h1 class="text-primary dash-trailing" id="accountability">Financial Accountability</h1>
			    <div class="row" style="display:flex; flex-wrap: wrap; flex-direction: row;">
		        	<div class="col-sm-4" style="display:flex">
			        	<div class="panel panel-default">
							<div class="panel-body">
								<h4>The Board of Directors</h4>
								<p class="small">Missions.Me operates at the highest level of financial integrity. All funds received are considered sacred. We know donations represent people's faith in the vision of the organization. Missions.Me operates on a budget predetermined and approved by a board of directors. The board of directors is comprised of respected and experienced leaders. Each project, staff salary, and outreach must be approved by the entire board of directors.</p>
							</div>
			        	</div><!-- end panel -->
			        </div><!-- end col -->
			        <div class="col-sm-4" style="display:flex">
			        	<div class="panel panel-default">
							<div class="panel-body">
								<h4>The Accounting</h4>
								<p class="small">The independent accounting firm, Richard J Boyse CPA, PC, compiles the finances given to Missions.Me. This accounting firm designates a staff CPA that organizes every Missions.Me financial contribution and ensures that each dollar is applied to its designated missionary or project. Missions.Me files an annual 990 to the IRS and also undergoes an annual voluntary audit.</p>
							</div>
			        	</div><!-- end panel -->
			        </div><!-- end col -->
			        <div class="col-sm-4" style="display:flex">
			        	<div class="panel panel-default">
							<div class="panel-body">
								<h4>The Promise</h4>
								<p class="small">The heartbeat of Missions.Me is to empower people to change the world. In order to accomplish this purpose, Missions.Me meticulously manages each contribution so that more lives can be transformed. Missions.Me is committed to maintaining a low administrative burden in order to ensure the highest-level return on investment for all donated funds.</p>
							</div>
			        	</div><!-- end panel -->
			        </div><!-- end col -->
			    </div><!-- end row -->
	        </div><!-- end col -->
		</div><!-- end row -->
	@endcomponent

	@component('section', ['class' => 'dark-bg-primary'])
		<div class="row">
			<div class="col-sm-4 col-sm-offset-4 text-center">
				<a href="/donate" class="btn btn-white-hollow btn-lg btn-block">Donate to the Organization</a>
			</div>
		</div><!-- end row -->
	@endcomponent
@stop