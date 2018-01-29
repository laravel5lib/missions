@extends('site.layouts.default')

@section('title', 'Academics - Missions.Me College')

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
				<div class="col-sm-8 col-sm-offset-2 col-md-5 col-md-offset-0 col-lg-4 col-lg-offset-0">
					<h2 class="text-serif" style="margin-top:0;display:inline-block;border-bottom:6px solid #f6323e;line-height:0.7em;">Academics</h2>
				</div>
				<div class="col-sm-8 col-sm-offset-2 col-md-7 col-md-offset-0 col-lg-8 col-lg-offset-0">
						<p class="small">Missions.Me College is committed to providing students with the best education. As a partner and extension site of Southeastern University, an accredited** university in Florida, our students experience just that. Our classroom environment, online learning, and hands on training offer a multi-faceted educational program. In addition to education and experience, students of Missions.Me College are steps away from the beautiful beaches of Southern California.</p>

						<p class="small">The Missions.Me College two or four year programs allow students to learn in a community environment and experience hands-on training. Upon graduation with Southeastern University, students will receive their associate’s or bachelor’s degrees.</p>

						<p class="small">As an extension site of Southeastern University, students will be enrolled in four classes (12 credits) each semester. An additional three credits will be granted as practicum credit each semester at no additional charge. Each student receives a total of 15 credits per semester.</p>

						<p class="small">Students can enroll in a wide number of degree programs offered through the Southeastern University extension site here at MMC.</p>

						<hr class="divider inv">
						<p class="small" style="font-size:11px;">**SEU Accreditation: Southeastern University is accredited by the Southern Association of Colleges and Schools Commission on Colleges to award associate, baccalaureate, masters, and doctorate degrees. Contact the Commission on Colleges at 1866 Southern Lane, Decatur, GA 30033-4097 or call 404-679-4500 for questions about the accreditation of Southeastern University.</p>
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
				<div class="col-sm-8 col-sm-offset-2 col-md-5 col-md-offset-0 col-lg-4 col-lg-offset-0">
					<h2 class="text-serif" style="margin-top:0;display:inline-block;border-bottom:6px solid #f6323e;line-height:0.7em;">Ministry Practicum</h2>
				</div>
				<div class="col-sm-8 col-sm-offset-2 col-md-7 col-md-offset-0 col-lg-8 col-lg-offset-0">
					<p class="small">Missions.Me College offers a great space for Southeastern University students to enjoy classes and experience hands-on ministry learning. Our hands-on approach is like no other higher learning program in the country. By traveling around the world, working with team members, and serving in ministry, we are committed to equipping this generation of leaders who will influence the culture and guide a life-changing organization.</p>

					<p class="small">The ministry practicum is a unique part of what sets our partnership with Southeastern University apart from other types of programs. Students get a chance to implement what they have been learning within the classroom. We believe hands-on, practical training and application is a crucial part of your education and will lead to a lifetime of success.</p>

					<p class="small">Students enrolled in one of Southeastern’s academic programs can earn up to 12 credit hours by participating in weekly ministry practicum.</p>
				</div><!-- end col -->
			</div><!-- end row -->
		</div><!-- end content-section -->
	</div><!-- end container -->
</div><!-- end white-bg -->
<hr class="divider" style="margin:0;">
@include('partials.college.questions')
@endsection