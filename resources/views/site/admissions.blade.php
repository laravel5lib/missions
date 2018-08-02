@extends('site.layouts.default')

@section('title', 'Admissions - Missions.Me College')

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
		    <li><a href="/student-life">Student Life</a></li>
		    <li class="active"><a href="/admissions">Admissions</a></li>
		    <li class="scrollNav"><a href="/admissions#faq">FAQ's</a></li>
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
		    	<li><a href="/student-life">Student Life</a></li>
		    	<li class="active"><a href="/admissions">Admissions</a></li>
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
				<div class="col-sm-6 col-md-4 col-md-offset-2">
					<h2 class="text-serif" style="margin-top:0;display:inline-block;border-bottom:6px solid #f6323e;line-height:0.7em;">Admissions</h2>
					<p class="small">There’s nothing like getting a letter in the mail with “Congratulations!” in big bold font across the letterhead. We want to get you to that point as quickly as possible. Sometimes application processes are painful, but not this one.</p>
				</div><!-- end col -->
				<div class="col-sm-6 col-md-4">
					<div class="panel panel-default text-center">
						<div class="panel-body">
							<h6 class="text-uppercase">Apply Now For</h6>
							<h2 class="text-serif" style="margin-top:10px;">Fall 2019</h2>
							<p><a class="btn btn-primary" target="_blank" href="https://missionsme.wufoo.com/forms/r1aqae3s12tu8md/">Apply Online</a></p>
						</div><!-- end panel-body -->
					</div><!-- end panel -->
				</div><!-- end col -->
			</div><!-- end row -->
		</div><!-- end content-section -->
	</div><!-- end container -->
</div>
<hr class="divider" style="margin:0;">
<div class="gray-bg-lighter" id="faq">
	<div class="container">
		<div class="content-section">
			<div class="row">
				<div class="col-sm-3 col-md-2 col-md-offset-2">
					<h2 class="text-serif" style="margin-top:0;display:inline-block;border-bottom:6px solid #f6323e;line-height:0.7em;">FAQ's</h2>
				</div>
				<div class="col-sm-7 col-md-6">
					<div class="panel-group" id="accordion">
						<div class="panel panel-default panel-primary">
						    <div class="panel-heading">
						      	<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
						          What are the components of the program?
						        </a>
						    </div>
						    <div id="collapseOne" class="panel-collapse collapse">
						      <div class="panel-body">
						        <p class="small">The main components of Missions.Me College is to grow, serve, lead and go. The vision of Missions.Me is what drives our students to live the impossible. Our students have the opportunity to earn an associates or bachelors degree online with Southeastern University. As part of their education, students will earn college credit by serving within our ministry and leading others to live the impossible. Students will also attend at least one mission trip a year.</p>
						      </div>
						    </div>
						  </div>

						  <div class="panel panel-default panel-primary">
						    <div class="panel-heading">
						        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
						          How does MMC work with Southeastern University?
						        </a>
						    </div>
						    <div id="collapseThree" class="panel-collapse collapse">
						      <div class="panel-body">
						        <p class="small">MMC is an official extension campus of Southeastern University. This means that by virtue of your attendance, you are enrolled in a higher education university. Depending on your goals and previous education, you can complete an Associates Degree and/or Bachelors Degree online with Southeastern University.</p>
						      </div>
						    </div>
						</div><!-- end panel -->
						<div class="panel panel-default panel-primary">
						    <div class="panel-heading">
						        <a data-toggle="collapse" data-parent="#accordion" href="#collapseNine">
						          Can I get a degree at MMC?
						        </a>
						    </div>
						    <div id="collapseNine" class="panel-collapse collapse">
						      <div class="panel-body">
						        <p class="small">Yes. you have the opportunity to receive an Associates degree or a Bachelor of Science degree from Southeastern University.</p>

								<p class="small">Missions.Me College is accredited through Southeastern University by the Southern Association of Colleges and Schools Commission on Colleges to award associate and baccalaureate degrees. Contact the Commission on Colleges at 1866 Southern Lane, Decatur, GA 30033-4097 or call 404-679-4500 for questions about the accreditation of Southeastern University.</p>
						      </div>
						    </div>
						</div><!-- end panel -->
						<div class="panel panel-default panel-primary">
						    <div class="panel-heading">
						        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTen">
						          How long is the program?
						        </a>
						    </div>
						    <div id="collapseTen" class="panel-collapse collapse">
						      <div class="panel-body">
						        <p class="small">Each year commitment is 11 months long. Students are required a commitment minimum of 1 year (11 months). Depending on a students academic goals, students can be enrolled in Missions.Me College for 1-4 years.</p>

								<p class="small">Students will be enrolled in classes through Southeastern University in the Fall and Spring. During the summer months, students will be helping with preparations for our summer missions trips and as well as attending a mission trip.</p>
						      </div>
						    </div>
						</div><!-- end panel -->
						<div class="panel panel-default panel-primary">
						    <div class="panel-heading">
						        <a data-toggle="collapse" data-parent="#accordion" href="#collapseEleven">
						          When is the application deadline?
						        </a>
						    </div>
						    <div id="collapseEleven" class="panel-collapse collapse">
						      <div class="panel-body">
						        <p class="small">Missions.Me College is currently accepting applications for Fall 2019. There are a limited number of spots in our program, so completing the application as possible is encouraged. Admission will be closed once the spots are filled.</p>
						      </div>
						    </div>
						</div><!-- end panel -->
						<div class="panel panel-default panel-primary">
						    <div class="panel-heading">
						        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
						          What does a normal week at MMC look like?
						        </a>
						    </div>
						    <div id="collapseFive" class="panel-collapse collapse">
						      <div class="panel-body">
						        <p class="small">Students will attend online classes throughout the week, participating in a full-time academic schedule. When not in class or study hall, students will partake in a hands-on experience learning to live the impossible with training from our Missions.Me staff. Students will attend Chapel services weekly. Students will also have the opportunity to earn college credit while serving within Missions.Me.</p>
						      </div>
						    </div>
						</div><!-- end panel -->
						<div class="panel panel-default panel-primary">
						    <div class="panel-heading">
						        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
						          Will I get to go home during the year?
						        </a>
						    </div>
						    <div id="collapseTwo" class="panel-collapse collapse">
						      <div class="panel-body">
						        <p class="small">Each MMC student will have vacation time and mandatory holidays where they are free to travel home and visit their families. A calendar will be provided to all students and parents with a full list of the time off and holidays.</p>
						      </div>
						    </div>
						</div><!-- end panel -->
						{{--  <div class="panel panel-default panel-primary">
						    <div class="panel-heading">
						        <a data-toggle="collapse" data-parent="#accordion" href="#collapseSix">
						          How does food and housing work?
						        </a>
						    </div>
						    <div id="collapseSix" class="panel-collapse collapse">
						      <div class="panel-body">
						       	<p class="small">You will be living with other students in our approved student housing complex. Rent and utilities are covered in the cost of your MMC tuition.</p>

								<p class="small">Food is not included in the general tuition. However, we can accommodate a meal plan into your tuition that includes a monthly dining debit card that you will be able to use for groceries, restaurants, and midnight pizza runs.</p>
						      </div>
						    </div>
					  	</div><!-- end panel -->  --}}
						<div class="panel panel-default panel-primary">
						    <div class="panel-heading">
						        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThirteen">
						          What are classes like?
						        </a>
						    </div>
						    <div id="collapseThirteen" class="panel-collapse collapse">
						      <div class="panel-body">
						        <p class="small">Every academic year, students are enrolled in two semesters. Students are enrolled as full time students completing two courses (six semester hours) every eight weeks during each semester. They are also enrolled in the ministerial practicum, which is college credit for serving within Missions.Me.</p>

								<p class="small">Students complete their education online with Southeastern University. Students are required to attend mandatory study hall times each day to allow the allotted time to complete class assignments. On site academic advising also occurs.</p>
						      </div>
						    </div>
						</div><!-- end panel -->
						<div class="panel panel-default panel-primary">
						    <div class="panel-heading">
						        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFourteen">
						          Can I get a job while being in the program?
						        </a>
						    </div>
						    <div id="collapseFourteen" class="panel-collapse collapse">
						      <div class="panel-body">
						        <p class="small">With a full time class schedule and being a part of Missions.Me College, students have a pretty demanding schedule. However, we understand that some of our students may need a job while attending MMC. That is why our schedules end at 5pm during the week and 12pm on Fridays. Weekends will also be available for students.</p>
						      </div>
						    </div>
						</div><!-- end panel -->
					</div><!-- end accordian -->
				</div><!-- end col -->
			</div><!-- end row -->
		</div><!-- end content-section -->
	</div><!-- end container -->
</div><!-- end white-bg -->
<hr class="divider" style="margin:0;">
@include('partials.college.questions')
@endsection