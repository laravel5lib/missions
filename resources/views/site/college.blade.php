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
		    <li class="active"><a href="/college">Home</a></li>
		    <li><a href="/about-mmc">About</a></li>
		    <li><a href="/academics">Academics</a></li>
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
				<li class="active"><a href="/college">Home</a></li>
		    	<li><a href="/about-mmc">About</a></li>
		    	<li><a href="/academics">Academics</a></li>
		    	<li><a href="/student-life">Student Life</a></li>
		    	<li><a href="/admissions">Admissions</a></li>
		    	<li><a href="/mmc-faqs">FAQ's</a></li>
			</ul>
		</div>
	</div><!-- end container -->
</div><!-- end white-bg -->
<div class="college-vid-bg">
  <video muted autoplay loop poster="images/college/home-placeholder.jpg" class="college-vid-bg-video">
    <source src="images/college/mmc-vid-bg.webm" type="video/webm">
    <source src="images/college/mmc-vid-bg.mp4" type="video/mp4">
    <source src="images/college/mmc-vid-bg.ogv" type="video/ogg">
  </video>
  <div class="container">
	  <div class="row">
	    <hr class="divider inv xlg">
	    <hr class="divider inv xlg">
	    <hr class="divider inv xlg">
	    <div class="col-md-12 col-md-offset-0">
	    	<img style="width:200px" src="images/college/mm-college-logo.png" alt="Missions.Me College">
	      <h1 class="text-hero text-white text-uppercase">Live the<br>impossible</h1>
	      <hr class="divider inv">
	      <a target="_blank" href="https://missionsme.wufoo.com/forms/r1aqae3s12tu8md/" class="btn btn-white-hollow btn-lg">Apply Now</a>
	      <hr class="divider inv xlg">
	      <h2 class="text-white text-center" style="margin:0;"><i class="fa fa-angle-down"></i></h2>
	      <hr class="divider inv xlg">
	      <hr class="divider inv xlg">
	      </div>
	    </div><!-- end col -->
	  </div><!-- end row -->
	</div><!-- end container -->
</div>
<div class="white-bg">
	<div class="container">
		<div class="content-section" style="padding:0; margin:-60px 0 60px;">
				<div class="row">
					<div class="col-sm-4">
						<div class="panel panel-default">
							<div class="video-outer">
								<div class="video-inner">
									<iframe src="https://player.vimeo.com/video/199504307?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
								</div>
							</div>
							<div class="panel-body text-center">
								<h6 class="text-uppercase">New Campus Location!</h6>
								<p class="small">We're expanding to Dana Point, CA</p>
								<a href="#" class="btn btn-primary btn-sm">Learn More</a>
							</div>
						</div><!-- end panel -->
					</div><!-- end col -->
					<div class="col-sm-4">
						<div class="panel panel-default">
							<div class="video-outer">
								<div class="video-inner">
									<iframe src="https://player.vimeo.com/video/89317516?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
								</div>
							</div>
							<div class="panel-body text-center">
								<h6 class="text-uppercase">New Campus Location!</h6>
								<p class="small">We're expanding to Dana Point, CA</p>
								<a href="#" class="btn btn-primary btn-sm">Learn More</a>
							</div>
						</div><!-- end panel -->
					</div><!-- end col -->
					<div class="col-sm-4">
						<div class="panel panel-default">
							<div class="video-outer">
								<div class="video-inner">
									<iframe src="https://player.vimeo.com/video/130333477" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
								</div>
							</div>
							<div class="panel-body text-center">
								<h6 class="text-uppercase">New Campus Location!</h6>
								<p class="small">We're expanding to Dana Point, CA</p>
								<a href="#" class="btn btn-primary btn-sm">Learn More</a>
							</div>
						</div><!-- end panel -->
					</div><!-- end col -->
				</div><!-- end row -->
				<hr class="divider inv lg">
			<div class="row">
				<div class="col-sm-12 text-center">
					<h2>The big dreams in your heart <span class="text-primary">are not</span> an accident.</h2>
				</div>
			</div><!-- end row -->
		</div>
	</div><!-- end container -->
</div><!-- end white-bg -->
<!-- MODAL -->
<div class="modal video-modal fade" id="westVid" tabindex="-1" role="dialog" aria-labelledby="modal-video-label">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-video">
                    <div class="video-outer">
                      <div class="video-inner">
                        <iframe src="https://player.vimeo.com/video/199504307?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="gray-bg-lighter" id="who-we-are">
	<div class="container">
	<div class="content-section">
		<div class="row" style="display:flex; flex-wrap: wrap; flex-direction: row;">
					<div class="col-sm-4" style="display:flex">
						<div class="panel panel-default">
							<div class="panel-body">
								<h5 class="text-primary">Vision</h5>
								<p class="small">We believe success is a byproduct of significance and strive to develop a premiere training facility where individuals are equipped with the tools to live impact filled lives.</p>
							</div><!-- end panel-body -->
						</div><!-- end panel -->
					</div><!-- end col -->
					<div class="col-sm-4" style="display:flex">
						<div class="panel panel-default">
							<div class="panel-body">
								<h5 class="text-primary">Grow</h5>
								<p class="small">MMC has developed a program to challenge students to grow spiritually, academically, physically and relationally. In this environment we believe success is inevitable.</p>
							</div><!-- end panel-body -->
						</div><!-- end panel -->
					</div><!-- end col -->
					<div class="col-sm-4" style="display:flex">
						<div class="panel panel-default">
							<div class="panel-body">
								<h5 class="text-primary">Serve</h5>
								<p class="small">MMC prepares students for a life of service, regardless of the career path they may chose. We have the desire to cultivate leaders in all areas of society.</p>
							</div><!-- end panel-body -->
						</div><!-- end panel -->
					</div><!-- end col -->
		</div><!-- end row -->
		<div class="row" style="display:flex; flex-wrap: wrap; flex-direction: row;">
					<div class="col-sm-4 col-sm-offset-2 col-xs-offset-0" style="display:flex">
						<div class="panel panel-default">
							<div class="panel-body">
								<h5 class="text-primary">Lead</h5>
								<p class="small">Some say leaders are born but we believe leaders are made. Our students become leaders marked by transparency, authenticity and character.</p>
							</div><!-- end panel-body -->
						</div><!-- end panel -->
					</div><!-- end col -->
					<div class="col-sm-4" style="display:flex">
						<div class="panel panel-default">
							<div class="panel-body">
								<h5 class="text-primary">Go</h5>
								<p class="small">We don’t just talk about global perspective; you experience it. Travel across the world fulfilling the great commission alongside your best friends and people who want to change the world just as much as you.</p>
							</div><!-- end panel-body -->
						</div><!-- end panel -->
					</div><!-- end col -->
		</div><!-- end row -->
	</div>
	</div><!-- end container -->
</div><!-- end white-bg -->
<div class="white-bg" id="academics">
	<div class="container">
	<div class="content-section">
		<div class="row">
			<div class="col-sm-6">
				<h3>Academics</h3>
				<p>MMC is committed to providing students with the very best education. Our classroom environment, online learning, and hands on training offer a multi-faceted educational program.</p>

				<p>With a Bachelors of Science and Associate of Arts degree programs accredited under Southeastern University in Florida, students will receive the educational training they need to be successful in whatever field they desire.</p>

				<p>Our hands-on approach is like no other higher learning program in the country. By traveling around the world, working with team members, and serving in ministry, we are committed to equipping this generation of leaders who will influence the culture and guide a life-changing organization.</p>
			</div><!-- end col -->
			<div class="col-sm-6">
				<h3>Missions</h3>
				<p>Missions is the heartbeat of our organization. At MMC you will have the opportunity to go on trips, participate on teams, build missions experiences, and learn how to operate a successful missions organization.</p>

				<p>At Missions.Me, nearly every aspect of the organization is fueled by people like you. Our staff will coach you as we push the limits of traditional missions together.</p>

				<p>We believe in you, and we believe you can change the world. The world of a child born on the streets in India. The world of a single-mother in Ghana who walks three miles everyday just for water. The world of a family next door in your neighborhood. Missions isn’t just for those called to live in another country their whole lives.</p>
				<a href="#" class="btn btn-sm btn-info">About Missions.Me</a>
				<a href="#" class="btn btn-sm btn-info">Go On A Trip With Us</a>
			</div><!-- end col -->
		</div><!-- end row -->
	</div>
	</div><!-- end container -->
</div><!-- end dark-bg-primary -->
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
				<h2 class="text-center">Winter Class of 2017</h2>
				<h5 class="text-uppercase text-center">Application Deadline</h5>
				<a target="_blank" href="https://missionsme.wufoo.com/forms/r1aqae3s12tu8md/" class="btn btn-lg btn-block btn-primary">December 1, 2016</a>
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