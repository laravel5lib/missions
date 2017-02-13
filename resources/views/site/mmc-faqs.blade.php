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
		    <li><a href="/college">Home</a></li>
		    <li><a href="/about-mmc">About</a></li>
		    <li class="active"><a href="/academics">Academics</a></li>
		    <li><a href="/student-life">Student Life</a></li>
		    <li><a href="/admissions">Admissions</a></li>
		    <li><a href="/mmc-faqs">FAQ's</a></li>
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
		    	<li><a href="/mmc-faqs">FAQ's</a></li>
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
<div class="gray-bg-lighter">
	<div class="container">
	<div class="content-section">
				<div class="row">
					<div class="col-sm-4">
						<div class="panel panel-default">
							<img class="img-responsive" src="images/college/students/jessica.jpg" alt="Jessica">
							<div class="panel-body">
								<h5 class="text-primary">Jessica R</h5>
								<p class="small">If you are looking for a college experience that is beyond the mundane and beyond the normal, then MMC is for you. I was sick of sitting in a normal classroom, feeling that there was something I could be doing now to fulfill the purpose and call God has on my life.</p>
							</div><!-- end panel-body -->
						</div><!-- end panel -->
					</div><!-- end col -->
					<div class="col-sm-4">
						<div class="panel panel-default">
							<img class="img-responsive" src="images/college/students/samuel.jpg" alt="Samuel">
							<div class="panel-body">
								<h5 class="text-primary">Samuel Z</h5>
								<p class="small">The Missions.Me College experience has given me the unique opportunity to not only serve alongside incredible leaders but also be a part of changing lives around the world. Where else can you receive a world-class education while being a vital part of nationwide transformation?</p>
							</div><!-- end panel-body -->
						</div><!-- end panel -->
					</div><!-- end col -->
					<div class="col-sm-4">
						<div class="panel panel-default">
							<img class="img-responsive" src="images/college/students/jasmine.jpg" alt="Jasmine">
							<div class="panel-body">
								<h5 class="text-primary">Jasmine R</h5>
								<p class="small">Missions.Me College has empowered me to grow exponentially in my leadership, relationship with God and servantship in ways I never thought I could have. I am so excited to see the new, exciting opportunities that are waiting in the coming year with MMC!</p>
							</div><!-- end panel-body -->
						</div><!-- end panel -->
					</div><!-- end col -->
				</div><!-- end row -->
	</div>
	</div><!-- end container -->
</div><!-- end white-bg -->
<div class="dark-bg-primary" id="community">
	<div class="container">
	<div class="content-section">
		<div class="row">
			<div class="col-sm-6">
				<h3>Community</h3>
				<p>The MMC team is a select group of people ready to passionately pursue Christ together and change the world at the same time.</p>

				<p>We hope to create a family culture. We believe in working hard and playing hard – together. At Missions.Me, we have more fun in the office than most people have at Disney World.</p>

				<p>You’ll make life-long friends, create lasting memories, and have stories that will make people say, “You did what?!” – in a good way.</p>
			</div><!-- end col -->
			<div class="col-sm-6">
				<div class="video-outer">
					<div class="video-inner">
						<iframe src="https://player.vimeo.com/video/89317516" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
					</div>
				</div>
			</div><!-- end col -->
		</div><!-- end row -->
	</div>
	</div><!-- end container -->
</div><!-- end gray-light-bg -->
<div class="white-bg" id="students">
	<div class="container">
	<div class="content-section">
		<div class="row">
			<div class="col-sm-6">
				<h3 class="text-primary">Admission</h3>
				<p>There’s nothing like getting a letter in the mail with “Congratulations!” in big bold font across the letterhead. We want to get you to that point as quickly as possible. Sometimes application processes are painful, but not this one. Fill out this form and we’ll let you know. <a target="_blank" href="https://missionsme.wufoo.com/forms/r1aqae3s12tu8md/">Apply Now</a></p>
				<h3 class="text-primary">Application Deadline</h3>
				<h2 class="text-center">Fall Class of 2017</h2>
				<h5 class="text-uppercase text-center">Application Deadline</h5>
				<a target="_blank" href="https://missionsme.wufoo.com/forms/r1aqae3s12tu8md/" class="btn btn-lg btn-block btn-primary">June 30, 2017</a>
			</div><!-- end col -->
			<div class="col-sm-6">
				<h3 class="text-primary">F.A.Q.</h3>
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
						</div>
	          		</div><!-- end accordian -->
			</div><!-- end col -->
		</div><!-- end row -->
	</div>
	</div><!-- end container -->
</div><!-- end white-bg -->
@endsection