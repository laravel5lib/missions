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
@endsection
@section('content')
<div class="content-page-header" style="max-height:500px;">
    <img class="img-responsive" src="images/projects/projects-header.jpg" alt="Projects">
</div><!-- end c-page-header -->
<div class="gray-lighter-bg">
    <div class="content-section" style="padding:0;">
      <div class="row" style="margin-left:0;margin-right:0;">
        <div class="col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 text-center home-vision">
          <h4>Bold opportunity, it's yours to lose. <a style="margin-left:10px;" class="btn btn-primary-hollow btn-sm launch-modal" data-toggle="modal" data-target="#video-modal"><i class="fa fa-play"></i></a></h4>
        </div><!-- end col -->
      </div><!-- end row -->
    </div><!-- end content-section -->
  <hr style="margin-top:0;margin-bottom:0;" class="divider xs">
</div><!-- end white-bg -->
<div class="white-bg">
	<div class="container">
		<div class="content-section">
				<div class="row">
					<div class="col-sm-6 col-md-4 hidden-xs">
						<hr class="divider lg inv hidden-xs hidden-sm hidden-md">
						<hr class="divider lg inv hidden-xs hidden-sm">
						<hr class="divider lg inv hidden-xs hidden-sm">
						<h3 style="color:#ec6190;">Overwhelming Opportunity</h3>
						<img style="width:60px;margin-bottom:25px;" src="images/projects/dash.png" alt="">
						<p>In the Western Hemisphere, Nicaragua is one of the most destitute nations, overwhelmed with opportunity for medical care, clean water, and food. In cooperation with the local government, this global team will take on poverty and bring life-saving aid to those who need it.</p>
					</div><!-- end col -->
					<div class="col-xs-10 col-xs-offset-1 col-sm-6 col-md-4 text-center visible-xs">
						<h3 style="color:#ec6190;">Overwhelming Opportunity</h3>
						<img style="width:60px;margin-bottom:25px;" src="images/projects/dash.png" alt="">
						<p class="small">In the Western Hemisphere, Nicaragua is one of the most destitute nations, overwhelmed with opportunity for medical care, clean water, and food. In cooperation with the local government, this global team will take on poverty and bring life-saving aid to those who need it.</p>
					<hr class="divider lg inv visible-xs">
					</div><!-- end col -->
					<div class="col-sm-6 col-md-offset-2">
						<a style="margin-left:10px;" class="launch-modal" data-toggle="modal" data-target="#video-modal"><img class="img-responsive" src="images/projects/need-thumb.png" alt="Need"></a>
					</div><!-- end col -->
				</div><!-- end row -->
		</div><!-- end content-section -->
	</div><!-- end container -->
</div><!-- end white-bg -->
<div class="gray-lighter-bg">
	<div class="container">
		<div class="content-section">
				<div class="row">
					<div class="col-xs-10 col-xs-offset-1 col-sm-5 col-sm-offset-1">
							<div class="panel-featured" style="position:absolute;left:16px;top:-5px;z-index:999;">
								<h6 class="btn btn-success btn-xs" style="border-radius:50px;"><i class="fa fa-usd icon-left"></i> 7,500</h6>
							</div>
							<div class="hover-container">
								<img class="img-responsive img-rounded" src="images/projects/medical-thumb.jpg" alt="Medical Clinics">
								<div class="hover-overlay" style="background-color: rgba(206, 255, 84, 1);">
									<h4 class="hover-text text-center text-uppercase">Medical Clinics</h4>
								</div>
							</div>
							<hr class="divider inv">
							<div class="row">
								<div class="col-xs-8">
									<a class="btn btn-primary btn-sm" href="{{ url('donate/medical-clinics') }}">Donate Now</a>
								</div><!-- end col -->
								<div class="col-xs-4">
									<p style="margin-top:5px;"><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">Details <i class="fa fa-angle-down"></i></a></p>
								</div><!-- end col -->
							</div><!-- end row -->
							<hr class="divider inv">
							<p id="collapseFour" class="panel-collapse collapse small">Our medical missionaries will come together to staff seven Medical Clinics across the nation offering medical diagnoses, treatment and the medicine required to treat hundreds of common conditions all free of charge. If your team would like to be a partner with us to save lives in Nicaragua, $7,500 will purchase and ship the medicine and supplies for one clinic.</p>
					<hr class="divider lg inv visible-xs">
					</div><!-- end col -->
					<div class="col-xs-10 col-xs-offset-1 col-sm-5 col-sm-offset-0">
							<div class="panel-featured" style="position:absolute;left:16px;top:-5px;z-index:999;">
								<h6 class="btn btn-success btn-xs" style="border-radius:50px;"><i class="fa fa-usd icon-left"></i> 7,000</h6>
							</div>
							<div class="hover-container">
								<img class="hover-image img-responsive img-rounded" src="images/projects/water-thumb.jpg" alt="Water Projects">
								<div class="hover-overlay" style="background-color: rgba(206, 255, 84, 1);">
									<h4 class="hover-text text-center text-uppercase">Clean Water Projects</h4>
								</div>
							</div>
							<hr class="divider inv">
							<div class="row">
								<div class="col-xs-8">
									<a class="btn btn-primary btn-sm" href="{{ url('donate/clean-water-projects') }}">Donate Now</a>
								</div><!-- end col -->
								<div class="col-xs-4">
									<p style="margin-top:5px;"><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Details <i class="fa fa-angle-down"></i></a></p>
								</div><!-- end col -->
							</div><!-- end row -->
							<hr class="divider inv">
							<p id="collapseThree" class="panel-collapse collapse small">Currently, there are impoverished communities who do not have access to free clean drinking water.  Thousands of people are either drinking contaminated water or spending a high percentage of their small wages on bottled water.  Our Clean Water Team Members will build water filtration systems in five of such communities.  The 500 gallon tanks will provide water for thousands of people in each community.  $7,000 will build a permanent system, including maintenance and training for local church leader who will manage the system after our team leaves.</p>
					</div><!-- end col -->
				</div><!-- end row -->
				<hr class="divider lg inv">
				<div class="row">
					<div class="col-xs-10 col-xs-offset-1 col-sm-5 col-sm-offset-1">
							<div class="panel-featured" style="position:absolute;left:16px;top:-5px;z-index:999;">
								<h6 class="btn btn-success btn-xs" style="border-radius:50px;"><i class="fa fa-usd icon-left"></i> 7,500</h6>
							</div>
							<div class="hover-container">
								<img class="hover-image img-responsive img-rounded" src="images/projects/food-thumb.jpg" alt="Meals">
								<div class="hover-overlay" style="background-color: rgba(206, 255, 84, 1);">
									<h4 class="hover-text text-uppercase">Meals</h4>
								</div>
							</div>
							<hr class="divider inv">
							<div class="row">
								<div class="col-xs-8">
									<a class="btn btn-primary btn-sm" href="{{ url('donate/meals') }}">Donate Now</a>
								</div><!-- end col -->
								<div class="col-xs-4">
									<p style="margin-top:5px;"><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Details <i class="fa fa-angle-down"></i></a></p>
								</div><!-- end col -->
							</div><!-- end row -->
							<hr class="divider inv">
							<p id="collapseOne" class="panel-collapse collapse small">1Nation1Day has been blessed with a large donation of several shipping containers loaded with enough meals to feed 270,000 people. Each container is valued at over $159,000 in nutritious food.  Our ministry squads will distribute these meals to communities in Nicaragua that desperately need it. Since the containers have already been donated, your sponsorship of $7,500 will cover shipping and distribution costs.</p>
					<hr class="divider lg inv visible-xs">
					</div><!-- end col -->
					<div class="col-xs-10 col-xs-offset-1 col-sm-5 col-sm-offset-0">
							<div class="hover-container">
								<img class="hover-image img-responsive img-rounded" src="images/projects/leadership-thumb.jpg" alt="Leadership Conference">
								<div class="hover-overlay" style="background-color: rgba(206, 255, 84, 1);">
									<h4 class="hover-text text-center text-uppercase">Start A Custom Fundraiser</h4>
								</div>
							</div>
							<hr class="divider inv">
							<div class="row">
								<div class="col-xs-8">
									<a class="btn btn-primary btn-sm" href="{{url('sponsor-a-project')}}">Start</a>
								</div><!-- end col -->
								<div class="col-xs-4">
									<p style="margin-top:5px;"><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Details <i class="fa fa-angle-down"></i></a></p>
								</div><!-- end col -->
							</div><!-- end row -->
							<hr class="divider inv">
							<div id="collapseTwo" class="panel-collapse collapse"><p class="small">Are you interested in setting up a custom fundraising page to help you raise funds for any of these projects? We'll hook you up. Learn more about how you can easily fundraise with Missions.Me.</p>
							</div>
					</div><!-- end col -->
				</div><!-- end row -->
		</div><!-- end content-section -->
	</div><!-- end container -->
</div><!-- end white-bg -->
<!-- MODAL -->
<div class="modal video-modal fade" id="video-modal" tabindex="-1" role="dialog" aria-labelledby="modal-video-label">
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
                        <iframe src="https://player.vimeo.com/video/223502805?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection