@extends('site.layouts.default')

@section('title', 'Contact Missions.Me')

@section('content')
<div class="content-page-header">
    <img class="img-responsive" src="images/contact/contact-header.jpg" alt="Contact Us">
    <div class="c-page-header-text">
    	<h1 class="text-uppercase dash-trailing">Contact Us</h1>
    </div><!-- end c-page-header-content -->
</div><!-- end c-page-header -->
<div class="white-bg">
	<div class="container">
	<div class="content-section">
		<div class="row">
			<div class="col-sm-4 text-center">
				<div class="panel panel-default">
					<div class="panel-body">
						<h5>Mailing Address</h5>
						<p>34270 Pacific Coast Hwy<br>
						Suite C<br>
						Dana Point, CA 92629</p>
						<hr class="divider lg">
						<h5>Phone</h5>
						<p>(877) 369-4532</p>
						<h5>Fax</h5>
						<p>(248) 247-1295</p>
					</div><!-- end panel-body -->
				</div><!-- end panel -->
			</div><!-- end col -->
			<div class="col-sm-8">
				<div class="col-sm-10 col-sm-offset-1">
					<p class="text-center">Day or night, Missions.Me is ready for you. Whether it be a question, comment, concern, or encouraging word, please do not hesitate to email or call! You can contact us by using the form below, or by any of these other means:</p>
					<hr class="divider inv">
					<div>
						<contact-form></contact-form>
					</div>
				</div>
			</div><!-- end col -->
		</div><!-- end row -->
	</div>
	</div><!-- end container -->
</div><!-- end white-bg -->
@endsection