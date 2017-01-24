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
		    <li><a href="/student-life">Student Life</a></li>
		    <li class="active"><a href="/admissions">Admissions</a></li>
		    <li class="scrollNav"><a href="/admissions#faq">FAQ's</a></li>
		    <li class="pull-right"><a class="btn btn-info btn-sm" target="_blank" href="https://missionsme.wufoo.com/forms/r1aqae3s12tu8md/">Apply Now</a></li>
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
							<h2 class="text-serif" style="margin-top:10px;">Fall 2017</h2>
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
						        <p class="small">As a part of MMC, you and your peers will live in beautiful Rochester, Michigan. We can promise that the year you spend at MMC will challenge you academically, spiritually, and relationally. You and your MMC peers will hit the ground running, attending at least one mission trip a year. You will live in an apartment with up to 3 other students who will quickly become your closest friends and be with you for the full 11-month term.</p>
						      </div>
						    </div>
						  </div>
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
						  </div>
						  <div class="panel panel-default panel-primary">
						    <div class="panel-heading">
						        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
						          How does MMC work with Southeastern University?
						        </a>
						    </div>
						    <div id="collapseThree" class="panel-collapse collapse">
						      <div class="panel-body">
						        <p class="small">MMC is an official extension campus of Southeastern University. This means that by virtue of your attendance, you are enrolled in a higher education university. After four years, whether on our campus or Southeastern, you will be a proud alumnus.</p>
						      </div>
						    </div>
						  </div>
						  <div class="panel panel-default panel-primary">
						    <div class="panel-heading">
						        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
						          What does a normal week at MMC look like?
						        </a>
						    </div>
						    <div id="collapseFive" class="panel-collapse collapse">
						      <div class="panel-body">
						        <p class="small">Students will attend class throughout the week, participating in a full-time academic schedule. When not in class, students will partake in a hands-on experience working with Missions.Me, as well as serving in the local church.</p>
						      </div>
						    </div>
						  </div>
						  <div class="panel panel-default panel-primary">
						    <div class="panel-heading">
						        <a data-toggle="collapse" data-parent="#accordion" href="#collapseSix">
						          How does food and housing work?
						        </a>
						    </div>
						    <div id="collapseSix" class="panel-collapse collapse">
						      <div class="panel-body">
						       	<p class="small">You will be living with other students in our approved student-housing complex. Our meal plan includes a monthly dining debit card that you will be able to use for groceries, restaurants, and midnight pizza runs.</p>
						      </div>
						    </div>
						  </div>
						  <div class="panel panel-default panel-primary">
						    <div class="panel-heading">
						        <a data-toggle="collapse" data-parent="#accordion" href="#collapseSeven">
						          When does the program start and end?
						        </a>
						    </div>
						    <div id="collapseSeven" class="panel-collapse collapse">
						      <div class="panel-body">
						        <p class="small">August 24, 2015 - August 1, 2016</p>
						      </div>
						    </div>
						  </div>
						  <div class="panel panel-default panel-primary">
						    <div class="panel-heading">
						        <a data-toggle="collapse" data-parent="#accordion" href="#collapseNine">
						          Can I get a degree at MMC?
						        </a>
						    </div>
						    <div id="collapseNine" class="panel-collapse collapse">
						      <div class="panel-body">
						        <p class="small">Yes. Upon completion you will receive either an Associates degree or a Bachelor of Science degree.</p>
						        <p class="small">Missions.Me College is accredited through Southeastern University by the Southern Association of Colleges and Schools Commission on Colleges to award associate and baccalaureate degrees.</p>
						      </div>
						    </div>
						  </div>
						</div><!-- end panel -->
					</div><!-- end accordian -->
				</div><!-- end col -->
			</div><!-- end row -->
		</div><!-- end content-section -->
	</div><!-- end container -->
</div><!-- end white-bg -->
@endsection