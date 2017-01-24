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
		    <li class="active"><a href="/about-mmc">About</a></li>
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
				<li><a href="/college">Home</a></li>
		    	<li class="active"><a href="/about-mmc">About</a></li>
		    	<li><a href="/academics">Academics</a></li>
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
				<div class="col-sm-12">
					<h2 class="text-serif" style="display:inline-block;border-bottom:6px solid #f6323e;line-height:0.7em;">About MMC</h2>
				</div>
			</div><!-- end row -->
		</div><!-- end content-section -->
	</div><!-- end container -->
</div>
<hr class="divider" style="margin:0;">
<div class="white-bg">
	<div class="container">
		<div class="content-section">
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3 text-center">
					<h2 class="text-serif" style="display:inline-block;border-bottom:6px solid #f6323e;line-height:0.7em;">The Vision</h2>
					<p>To create a movement of young leaders to <span class="text-primary text-serif"><em>Live The Impossible</em></span> through world-class leadership training, innovative academic instruction, real-world experience, and global outreach opportunities.</p>
				</div><!-- end col -->
			</div><!-- end row -->
		</div><!-- end content-section -->
	</div><!-- end container -->
</div><!-- end white-bg -->
<div class="mmc-cali-bg" style="background:url('/images/college/vision-bg.jpg') no-repeat bottom fixed;">
	<div class="container">
		<div class="content-section">
			<div class="row">
				<div class="col-sm-8 col-sm-offset-2 text-center">
					<h6 class="text-uppercase">Our Mission</h6>
					<h1 class="text-serif text-lightweight">Grow. Serve. Lead. Go.</h1>
				</div><!-- end col -->
			</div><!-- end row -->
		</div><!-- end content-section -->
	</div><!-- end container -->
</div><!-- end white-bg -->
<div class="white-bg">
	<div class="container">
		<div class="content-section">
			<div class="row">
				<div class="col-sm-8 col-sm-offset-2">
					<h4 class="text-serif text-lightweight text-primary">Grow</h4>
					<p class="small">MMC has developed a program to challenge students to grow spiritually, academically, physically and relationally. In this environment we believe success is inevitable.</p>
					<h4 class="text-serif text-lightweight text-primary">Serve</h4>
					<p class="small">MMC prepares students for a life of service, regardless of the career path they may chose. We have the desire to cultivate leaders in all areas of society.</p>
					<h4 class="text-serif text-lightweight text-primary">Lead</h4>
					<p class="small">Some say leaders are born but we believe leaders are made. Our students become leaders marked by transparency, authenticity and character.</p>
					<h4 class="text-serif text-lightweight text-primary">Go</h4>
					<p class="small">We don’t just talk about global perspective; you experience it. Travel across the world fulfilling the great commission alongside your best friends and people who want to change the world just as much as you.</p>
				</div><!-- end col -->
			</div><!-- end row -->
		</div><!-- end content-section -->
	</div><!-- end container -->
</div><!-- end white-bg -->
@endsection