@extends('site.layouts.default')

@section('content')
<div class="content-page-header">
    <img class="img-responsive" src="images/squad-leader/squad-leader-header.jpg" alt="Squad Leader">
    <div class="c-page-header-text">
    	<h1 class="text-uppercase dash-trailing">Squad Leader</h1>
    </div><!-- end c-page-header-content -->
</div><!-- end c-page-header -->
<div class="white-bg">
	<div class="container">
	<div class="content-section">
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
				<h2 class="text-center">Squad Leader Training</h2>
                <hr class="divider red-small">
                <hr class="divider inv sm">
                <p class="text-center">Congratulations, you have been nominated to lead a squad! As an official "Squad Leader", you'll need to view the four-part training series with the Missions.Me Country Directors at your convenience.  All four parts will be viewable here.</p>
                <p class="text-center"><strong>It is imperative that you view these sessions as well as read the Squad Leader Manual PDF document which can be downloaded below.</strong></p>
				<hr class="divider inv">
                <div class="row">
                    <div class="col-xs-12 col-sm-10 col-sm-offset-1 text-center">
                        <a class="btn btn-primary" href="{{ download_file('resources/SLmanual2017_dig.pdf') }}">
                           	<i class="fa fa-file-pdf-o icon-left"></i> Squad Leader Manual
                            <small>(2.4MB)</small>
                        </a>
                    </div>
                </div>
                <hr class="divider inv lg">
            </div><!-- end col -->
        </div><!-- end row -->
		<div class="row">
				<div class="row">
					<div class="col-xs-12 col-sm-6">
						<div class="panel panel-default">
							<div class="video-outer">
								<div class="video-inner">
									<iframe src="https://player.vimeo.com/video/274123289?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
								</div><!-- end video-inner -->
							</div><!-- end video-outer -->
							<div class="panel-body text-center">
								<h6 class="text-uppercase">Squad Leader &middot; Session 1</h6>
							</div><!-- end panel-body -->
						</div><!-- end panel -->
					</div><!-- end col -->
					<div class="col-xs-12 col-sm-6">
						<div class="panel panel-default">
							<div class="video-outer">
								<div class="video-inner">
									<iframe src="https://player.vimeo.com/video/274123281?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
								</div><!-- end video-inner -->
							</div><!-- end video-outer -->
							<div class="panel-body text-center">
								<h6 class="text-uppercase">Squad Leader &middot; Session 2</h6>
							</div><!-- end panel-body -->
						</div><!-- end panel -->
					</div><!-- end col -->
				</div><!-- end row -->
			</div><!-- end col -->
		</div><!-- end row -->
	</div>
	</div><!-- end container -->
</div><!-- end white-bg -->
@endsection