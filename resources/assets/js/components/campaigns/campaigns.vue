<template>
	<div id="carousel-example-generic" class="carousel slide campaign-carousel" data-ride="carousel">
		<div class="carousel-inner">
			<div class="item active">
				<img src="../images/advance/adv-2018-header.jpg" alt="Advance 2018">
				<div class="carousel-caption">
					<h6 class="text-uppercase">Advance 2018</h6>
					<h3>3 Nations 1 Generation</h3>
					<p>On July 21-29, 2018 we will take the next step in discipling nations by returning to all 3 1Nation1Day nations.</p>
					<!--<a href="http://1nation1day.com" class="btn btn-primary btn-sm">Learn About 2018 Trips</a>-->
				</div><!-- end carousel -->
			</div><!-- end item -->
		</div><!-- end carousel-inner -->
		<div class="featured-tag">
			<a class="btn btn-info btn-sm hidden-xs" style="border-radius:50px;"><i class="fa fa-bolt icon-left"></i> Featured</a>
		</div><!-- end featured-tag -->
	</div><!-- end carousel -->
	<hr class="divider inv xlg">

	<div class="container" v-if="campaigns.length < 1">
			<div class="col-sm-8 col-sm-offset-2 text-center">
				<h3>Looking for 2018 trips?</h3>
				<hr class="divider inv sm">
				<p>Be the first on the list to know about 2018 trips.</p>
				<hr class="divider inv">
				<a href="http://eepurl.com/cZnArz" target="_blank" class="btn btn-primary">Notify Me!</a>
			</div><!-- end col -->
	</div><!-- end container -->

	<div class="container" style="display:flex; flex-wrap: wrap; flex-direction: row;" v-if="campaigns.length > 0">
		<spinner v-ref:spinner size="sm" text="Loading"></spinner>
		<div class="col-xs-12 col-sm-6 col-md-4" v-for="campaign in campaigns|limitBy campaignsLimit" style="display:flex">
			<div class="panel panel-default">
				<a class="hidden-xs hidden-sm" :href="campaign.page_url" role="button">
					<img :src="campaign.avatar+'?w=400&h=400&fit=stretch'" :alt="campaign.name" class="img-responsive">
				</a>
				<div style="min-height:220px;" class="panel-body">
					<h6 style="text-transform:uppercase;letter-spacing:1px;font-size:10px;"><i
							class="fa fa-map-marker"></i> {{campaign.country}}</h6>
					<a :href="campaign.page_url" role="button">
						<h5 style="text-transform:capitalize;" class="text-primary">{{campaign.name}}</h5>
					</a>
					<h6 style="font-size:12px;">
						{{campaign.started_at | moment 'll'}} -
						{{campaign.ended_at | moment 'll'}}
					</h6>
					<hr class="divider lg"/>
					<p style="font-size:12px;" class="small">{{campaign.description}}</p>
				</div><!-- end panel-body -->
			</div><!-- end panel -->
		</div><!-- end col -->
	</div>
	<hr class="divider inv xlg">
	<div class="white-bg">
		<div class="container">
			<div class="content-section">
				<div class="row">
					<div class="col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 text-center">
						<h2 class="text-primary">Missions.Me provides a custom opportunity. We create experiences that maximize your gifts and are tailored to your passion.</h2>
					</div><!-- end col -->
				</div><!-- end row -->
			</div><!-- end content-section -->
		</div><!-- end container -->
	</div><!-- end white-bg -->
	<div class="white-bg">
		<div class="row row-no-margin">
			<div class="col-sm-2 col-xs-4 col-no-padding">
				<img class="img-responsive" src="images/why-mm/collage/collage1.jpg" alt="">
			</div>
			<div class="col-sm-2 col-xs-4 col-no-padding">
				<img class="img-responsive" src="images/why-mm/collage/collage3.jpg" alt="">
			</div>
			<div class="col-sm-2 col-xs-4 col-no-padding">
				<img class="img-responsive" src="images/why-mm/collage/collage4.jpg" alt="">
			</div>
			<div class="col-sm-2 col-xs-4 col-no-padding">
				<img class="img-responsive" src="images/why-mm/collage/collage5.jpg" alt="">
			</div>
			<div class="col-sm-2 col-xs-4 col-no-padding">
				<img class="img-responsive" src="images/why-mm/collage/collage8.jpg" alt="">
			</div>
			<div class="col-sm-2 col-xs-4 col-no-padding">
				<img class="img-responsive" src="images/why-mm/collage/collage9.jpg" alt="">
			</div>
		</div>
	</div><!-- end white-bg -->
	<div class="white-bg">
		<div class="row row-no-margin">
			<div class="col-sm-2 col-xs-4 col-no-padding">
				<img class="img-responsive" src="images/why-mm/collage/collage10.jpg" alt="">
			</div>
			<div class="col-sm-2 col-xs-4 col-no-padding">
				<img class="img-responsive" src="images/why-mm/collage/collage12.jpg" alt="">
			</div>
			<div class="col-sm-2 col-xs-4 col-no-padding">
				<img class="img-responsive" src="images/why-mm/collage/collage13.jpg" alt="">
			</div>
			<div class="col-sm-2 col-xs-4 col-no-padding">
				<img class="img-responsive" src="images/why-mm/collage/collage14.jpg" alt="">
			</div>
			<div class="col-sm-2 col-xs-4 col-no-padding">
				<img class="img-responsive" src="images/why-mm/collage/collage15.jpg" alt="">
			</div>
			<div class="col-sm-2 col-xs-4 col-no-padding">
				<img class="img-responsive" src="images/why-mm/collage/collage18.jpg" alt="">
			</div>
		</div>
	</div><!-- end white-bg -->
	<div class="gray-lighter-bg">
		<div class="container">
			<div class="content-section">
				<div class="row">
					<div class="col-xs-10 col-xs-offset-1">
						<div class="row">
							<div class="col-xs-12">
								<h6 class="text-uppercase text-center">A Trip For Everyone</h6>
								<h1 class="text-center">Choose A Role</h1>
								<hr class="divider red-small lg">
								<hr class="divider inv lg">
							</div><!-- end col -->
						</div><!-- end row -->
						<div class="row" style="display:flex; flex-wrap: wrap; flex-direction: row;">
							<div class="col-sm-4 col-xs-12" style="display:flex">
								<div class="panel">
									<div style="position: absolute;left: 25px;top: 15px;z-index: 999;">
										<a class="btn btn-primary-hollow btn-sm launch-modal pull-right" data-toggle="modal" data-target="#video-modal-one"><i class="fa fa-play"></i></a>
									</div>
									<img class="img-responsive" src="images/why-mm/missionary.jpg" alt="">
									<div class="panel-body">
										<h5>Missionary</h5>
										<p class="small">Anyone age 13+ can be an M.M missionary. Set out on the journey
											with your friend or family to bring a message of love and hope along with an
											unforgettable cultural experience.</p>
									</div>
								</div>
							</div><!-- end col -->
							<div class="col-sm-4 col-xs-12" style="display:flex">
								<div class="panel">
									<div style="position: absolute;left: 25px;top: 15px;z-index: 999;">
										<a class="btn btn-primary-hollow btn-sm launch-modal pull-right" data-toggle="modal" data-target="#video-modal-two"><i class="fa fa-play"></i></a>
									</div>
									<img class="img-responsive" src="images/why-mm/medical-missionary.jpg" alt="">
									<div class="panel-body">
										<h5>Medical Missionary</h5>
										<p class="small">Treat, diagnose, prescribe, and share the love of Jesus. Anyone
											in a medical field can serve, including students currently enrolled in
											school. Physicians, Dentists, Nurses, and all assistants needed.</p>
									</div>
								</div>
							</div><!-- end col -->
							<div class="col-sm-4 col-xs-12" style="display:flex">
								<div class="panel">
									<div style="position: absolute;left: 25px;top: 15px;z-index: 999;">
										<a class="btn btn-primary-hollow btn-sm launch-modal pull-right" data-toggle="modal" data-target="#video-modal-three"><i class="fa fa-play"></i></a>
									</div>
									<img class="img-responsive" src="images/why-mm/influencer.jpg" alt="">
									<div class="panel-body">
										<h5>Influencer</h5>
										<p class="small">Missionaries with extensive ministry experience, ready to share their gifts of leadership and influence one on one with school faculty, at university forums, or in local churches.</p>
									</div>
								</div>
							</div><!-- end col -->
						</div><!-- end row -->
						<div class="row" style="display:flex; flex-wrap: wrap; flex-direction: row;">
							<div class="col-sm-4 col-xs-12" style="display:flex">
								<div class="panel">
									<div style="position: absolute;left: 25px;top: 15px;z-index: 999;">
										<a class="btn btn-primary-hollow btn-sm launch-modal pull-right" data-toggle="modal" data-target="#video-modal-four"><i class="fa fa-play"></i></a>
									</div>
									<img class="img-responsive" src="images/why-mm/speaker.jpg" alt="">
									<div class="panel-body">
										<h5>Pastor/Speaker</h5>
										<p class="small">Share your gift of leadership with the team by leading a
											morning devo then impart spiritual wisdom into local church leadership. Let
											us create a custom schedule that makes the most of your valuable time.</p>
									</div><!-- end panel-body -->
								</div><!-- end panel -->
							</div><!-- end col -->
							<div class="col-sm-4 col-xs-12" style="display:flex">
								<div class="panel">
									<div style="position: absolute;left: 25px;top: 15px;z-index: 999;">
										<a class="btn btn-primary-hollow btn-sm launch-modal pull-right" data-toggle="modal" data-target="#video-modal-five"><i class="fa fa-play"></i></a>
									</div>
									<img class="img-responsive" src="images/why-mm/business-person.jpg" alt="">
									<div class="panel-body">
										<h5>Business Leader</h5>
										<p class="small">A shortened “Business Class” trip is available on select trips.
											You will experience conferences, stadium outreaches, street ministry, and
											connect with God in a whole new way. All trips will have you back on Sunday
											night, ready for Monday.</p>
									</div>
								</div>
							</div><!-- end col -->
							<div class="col-sm-4 col-xs-12" style="display:flex">
								<div class="panel">
									<div style="position: absolute;left: 25px;top: 15px;z-index: 999;">
										<a class="btn btn-primary-hollow btn-sm launch-modal pull-right" data-toggle="modal" data-target="#video-modal-six"><i class="fa fa-play"></i></a>
									</div>
									<img class="img-responsive" src="images/why-mm/media-missionary.jpg" alt="">
									<div class="panel-body">
										<h5>Media Missionary</h5>
										<p class="small">Tell the story of life change! M.M needs you to film, shoot,
											interview, edit, export, and be creative! Experience a whole new world
											through the lens of a camera while capturing the most exciting moments of
											the trip.</p>
									</div>
								</div>
							</div><!-- end col -->
						</div><!-- end row -->
					</div><!-- end col -->
				</div><!-- end row -->
			</div><!-- end content-section -->
		</div><!-- end container -->
	</div><!-- end gray-lighter-bg -->
	<div class="white-bg">
		<div class="container">
			<div class="content-section">
				<div class="row">
					<div class="col-sm-6 col-sm-offset-0 col-xs-10 col-xs-offset-1">
						<h6 class="text-uppercase text-primary">It's So Easy</h6>
						<h1 class="text-primary dash-trailing">We've Got You</h1>
						<p class="large-type">Logistics, we got it covered. Missions.Me makes missions simple by taking
							care of all of your transportation, hotel, food, training, translators and ministry schedule
							needs.</p>
						<hr class="divider inv">
						<a class="btn btn-primary" href="/contact">Speak To A Rep</a>
						<hr class="divider inv visible-xs visible-sm">
					</div><!-- end col -->
					<div class="col-sm-6 col-sm-offset-0 col-xs-10 col-xs-offset-1">
						<img class="img-responsive img-rounded" src="images/why-mm/staff.jpg"/>
					</div><!-- end col -->
				</div><!-- end row -->
			</div><!-- end content-section-->
		</div><!-- end container -->
	</div><!-- end red-bg -->
	<div class="white-bg">
		<div class="row row-no-margin">
			<div class="col-sm-6 col-no-padding">
				<img class="img-responsive" src="images/why-mm/miami-lawn.jpg" alt="">
			</div>
			<div class="col-sm-6 col-no-padding">
				<img class="img-responsive" src="images/why-mm/miami-conf.jpg" alt="">
			</div>
		</div>
	</div><!-- end white-bg -->
	<div class="bg-primary">
		<div class="container">
			<div class="content-section">
				<div class="row">
					<div class="col-sm-6 col-sm-offset-0 col-xs-10 col-xs-offset-1">
						<h1 class="text-primary-darker dash-trailing-dark">Level Of Impact</h1>
						<p class="large-type">We are interested in the transformation of individuals, communities, cities, and nations. Every outreach we produce is measured through the lens of measurable transformation.  In one week's time, you and your team will speak to entire schools, impact neighborhoods, empower churches, and even fill stadiums. We believe a short-term team must serve a long-term, sustainable goal. That’s why when your team leaves our international partners continue to serve the communities you impacted, so that we make disciples.</p>
						<hr class="divider inv visible-xs visible-sm">
					</div><!-- end col -->
					<div class="col-sm-6 col-sm-offset-0 col-xs-10 col-xs-offset-1">
						<img class="img-responsive img-rounded" src="images/why-mm/stadium-impact.jpg"/>
					</div><!-- end col -->
				</div><!-- end row -->
			</div><!-- end content-section -->
		</div><!-- end container -->
	</div><!-- end dark-bg-primary -->
	<div class="gray-lighter-bg">
		<div class="container">
			<div class="content-section">
				<div class="row">
					<div class="col-sm-6 col-sm-offset-0 col-xs-10 col-xs-offset-1">
						<img class="img-responsive img-rounded" src="images/why-mm/group-security.jpg"/>
					</div><!-- end col -->
					<div class="col-sm-6 col-sm-offset-0 col-xs-10 col-xs-offset-1">
						<h1 class="text-primary dash-trailing">Safety and Security</h1>
						<!-- <p>Missions.Me's first priority is safety. Our partners have successfully hosted thousands of American missionaries for over 25 years. Our leadership provides in depth training to create a safe experience for every missionary. Our projects are managed in cooperation with the local police and hired security so that each ministry context is safe. Multiple leaders care for each missionary and guidelines are faceted so that no person is ever alone. We also do background checks on all potential leaders. Missions.Me has a proven track record of safety and security that keeps our teams coming back.</p> -->
						<p>Missions.Me's first priority is safety. Our partners have successfully hosted thousands of
							missionaries for over 25 years. Our projects are managed in cooperation with the
							local police and hired security so that each ministry context is safe.</p>
						<blockquote>"I have traveled with Missions.Me in many countries and have witnessed first-hand
							their organization and safety measures. I am completely confident recommending a Missions.Me experience to families."
							<footer>Sue, Mother from Lancaster, PA</footer>
						</blockquote>
					</div><!-- end col -->
				</div><!-- end row -->
			</div><!-- end content-section -->
		</div><!-- end container -->
	</div><!-- end dark-bg-primary -->
	<div class="gray-light-bg">
		<div class="container">
			<div class="content-section">
				<div class="row">
					<div class="col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
						<h1 class="text-center">Trip Difficulty Ratings</h1>
						<hr class="divider red-small lg">
						<hr class="divider inv lg">
					</div><!-- end col -->
				</div><!-- end row -->
				<div class="row text-center">
					<div class="col-sm-4 col-sm-offset-0 col-xs-10 col-xs-offset-1">
						<img class="img-lg" src="images/why-mm/level1.png" alt="">
						<hr class="divider inv">
						<p>These trips are great for those getting started in the world-changing business. First
							timers of all ages area welcome.</p>
						<hr class="divider inv lg visible-xs">
					</div>
					<div class="col-sm-4 col-sm-offset-0 col-xs-10 col-xs-offset-1">
						<img class="img-lg" src="images/why-mm/level2.png" alt="">
						<hr class="divider inv">
						<p>If Level 1 proved to be a piece of cake, you’re ready for a Level 2 adventure. A little
							tougher, a little sweatier, but much more sweeter.</p>
						<hr class="divider inv lg visible-xs">
					</div>
					<div class="col-sm-4 col-sm-offset-0 col-xs-10 col-xs-offset-1">
						<img class="img-lg" src="images/why-mm/level3.png" alt="">
						<hr class="divider inv">
						<p>Chuck Norris. Mr. T. Annie Oakley. Yep, they’re all level 3 missionaries. It may not be
							physically tough, but it’ll be a challenge.</p>
						<hr class="divider inv lg visible-xs">
					</div>
				</div>
			</div><!-- end content-section -->
		</div><!-- end container -->
	</div><!-- end dark-bg-primary -->
	<!-- MISSIONARY MODAL -->
	<div class="modal video-modal fade" id="video-modal-one" tabindex="-1" role="dialog" aria-labelledby="modal-video-label">
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
	                        <iframe src="https://player.vimeo.com/video/187239218?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
	                      </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div><!-- end modal -->
	<!-- MEDICAL MODAL -->
	<div class="modal video-modal fade" id="video-modal-two" tabindex="-1" role="dialog" aria-labelledby="modal-video-label">
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
	                        <iframe src="https://player.vimeo.com/video/185716159?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
	                      </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div><!-- end modal -->
	<!-- INFLUENCER MODAL -->
	<div class="modal video-modal fade" id="video-modal-three" tabindex="-1" role="dialog" aria-labelledby="modal-video-label">
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
	                        <iframe src="https://player.vimeo.com/video/185972725?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
	                      </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div><!-- end modal -->
	<!-- PASTOR MODAL -->
	<div class="modal video-modal fade" id="video-modal-four" tabindex="-1" role="dialog" aria-labelledby="modal-video-label">
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
	                        <iframe src="https://player.vimeo.com/video/187199738?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
	                      </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div><!-- end modal -->
	<!-- BUSINESS PERSON MODAL -->
	<div class="modal video-modal fade" id="video-modal-five" tabindex="-1" role="dialog" aria-labelledby="modal-video-label">
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
	                        <iframe src="https://player.vimeo.com/video/187199859?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
	                      </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div><!-- end modal -->
	<!-- MEDIA MODAL -->
	<div class="modal video-modal fade" id="video-modal-six" tabindex="-1" role="dialog" aria-labelledby="modal-video-label">
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
	                        <iframe src="https://player.vimeo.com/video/185716158?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
	                      </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div><!-- end modal -->
</template>
<script type="text/javascript">
	export default{
		name: 'campaigns',
		data(){
			return {
				campaigns: [],
				campaignsLimit: 3,
				resource: this.$resource('campaigns?published=true&current=true')
			}
		},
		methods: {
			seeAll(){
				this.campaignsLimit = this.campaigns.length
			}
		},
		ready(){
			// this.$refs.spinner.show();
			this.resource.query().then(function (campaigns) {
				this.campaigns = campaigns.data.data;
				// this.$refs.spinner.hide();
			}, function (error) {
				// this.$refs.spinner.hide();
				//TODO error alert message
			}).then(function () {

			});
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
		}
	}
</script>

