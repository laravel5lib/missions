@extends('site.layouts.default')

@section('scripts')
<script>
    $('ul.scrollNav li a').easyScroller({ scrollDownSpeed: 500 })
</script>
@endsection

@section('content')
<div class="content-page-header">
    <img class="img-responsive" src="images/college/college-header.jpg" alt="College">
    <div class="c-page-header-text">
    	<h1 class="text-uppercase dash-trailing">College Financial</h1>
    </div><!-- end c-page-header-content -->
</div><!-- end c-page-header -->

<div class="gray-lighter-bg">
	<div class="white-bg">
		<ul class="nav nav-tabs">
		    <li class="active"><a href="/college#who-we-are">Who We Are</a></li>
		    <li><a href="/college#academics">Academics</a></li>
		    <li><a href="/college#community">Community</a></li>
		    <li><a href="/college#students">Prospective Students</a></li>
		    <li><a href="/college-financial">Financial Aid</a></li>
		    <li><a href="/college#missions">Missions</a></li>
		    <li><a target="_blank" href="https://missionsme.wufoo.com/forms/r1aqae3s12tu8md/">Apply Now</a></li>
		</ul>
	</div><!-- end white-bg -->
	<div class="container">
	<div class="content-section">
		<div class="row">
			<div class="col-sm-6">
				<h3 class="text-primary">Financial Aid</h3>
				<p>College is one of the most substantial and important investments you will make in your life. That’s why Missions.Me College and Southeastern University want to provide as much assistance as possible to make sure our graduates leave school without the crushing burden of student loan debt. We offer numerous programs designed to reward high-achieving students and to provide needed financial aid to deserving families.</p>
				<a target="_blank" href="https://fafsa.ed.gov/" class="btn btn-lg btn-primary">Enroll in FAFSA</a>
			</div><!-- end col -->
			<div class="col-sm-6">
				<h3 class="text-primary">Highlights of Southeastern University's Financial Aid Offerings</h3>
					<ul>
						<li>More than 97% of our students receive financial aid.</li>
						<li>Students on average receive about $16,000 in grant awards each year.</li>
						<li>SEU students are still eligible for state and federal aid.</li>
					</ul>
				<p>In order to be considered for any financial assistance, all Missions.Me andSoutheastern students must submit the Free Application for Federal Student Aid (FAFSA). Southeastern’s FAFSA code is #001521. Also, upon acceptance to SEU, you will receive a letter with instructions on how to complete the Student Information Sheet (SIS) online.</p>

				<p>For a complete list and descriptions of financial aid available at Southeastern University, follow the links on this page. If you have specific questions, call 877.369.4532 or email info@missions.me</p>
			</div><!-- end col -->
		</div><!-- end row -->
	</div>
	</div><!-- end container -->
</div><!-- end dark-bg-primary -->
<div class="dark-bg-primary">
	<div class="container">
	<div class="content-section">
		<div class="row">
			<div class="col-sm-6">
				<h3 class="text-white">Tuition & Fees</h3>
				<p>The MMC and Southeastern University fees are subject to change each year. If you have questions regarding any of the fees or want more details about financial aid options, contact us.</p>
				<hr class="divider inv lg">
				<p class="text-center"><a href="tel:877.369.4532" class="btn btn-lg btn-white-hollow">877.369.4532</a>
			</div><!-- end col -->
			<div class="col-sm-6">
				<h3 class="text-white">Expenses Per Semester</h3>
				<div class="col-sm-8">
					<h5 class="text-uppercase">Tuition For SEU</h5>
				</div>
				<div class="col-sm-4 text-right">
					<h5 class="text-uppercase">$3,300</h5>
				</div>
				<div class="col-sm-8">
					<h5 class="text-uppercase">MMC Fees</h5>
				</div>
				<div class="col-sm-4 text-right">
					<h5 class="text-uppercase">$1,248</h5>
				</div>
				<div class="col-sm-8">
					<h5 class="text-uppercase">Site Fee & Housing Costs</h5>
				</div>
				<div class="col-sm-4 text-right">
					<h5 class="text-uppercase">$1,260</h5>
				</div>
				<div class="col-sm-8">
					<h5 class="text-uppercase">Trip Fee</h5>
				</div>
				<div class="col-sm-4 text-right">
					<h5 class="text-uppercase">$750</h5>
				</div>
				<div class="col-sm-12">
					<hr class="divider">
				</div>
				<div class="col-sm-8">
					<h5 class="text-uppercase">Cost Per Semester</h5>
				</div>
				<div class="col-sm-4 text-right">
					<h5 class="text-uppercase">$6,558</h5>
				</div>
				<div class="col-sm-8">
					<h5 class="text-uppercase">Cost For 12 Months</h5>
				</div>
				<div class="col-sm-4 text-right">
					<h5 class="text-uppercase">$13,116</h5>
				</div>
			</div><!-- end col -->
		</div><!-- end row -->
	</div>
	</div><!-- end container -->
</div><!-- end white-bg -->
@endsection