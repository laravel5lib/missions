@extends('site.layouts.default')

@section('content')
<div class="content-page-header">
    <img class="img-responsive" src="images/sponsor/sponsor-header.jpg" alt="Sponsor">
    <div class="c-page-header-text">
    	<h1 class="text-uppercase dash-trailing">Become A Sponsor</h1>
    </div><!-- end c-page-header-content -->
</div><!-- end c-page-header -->
<div class="white-bg">
	<div class="container">
	<div class="content-section">
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
				<hr class="divider inv">
				<sponsor-project-form></sponsor-project-form>
			</div><!-- end col -->
		</div><!-- end row -->
	</div>
	</div><!-- end container -->
</div><!-- end white-bg -->
@endsection