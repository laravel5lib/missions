@extends('site.layouts.default')

@section('content')
<div class="content-page-header">
    <img class="img-responsive" src="images/support/support-header.jpg" alt="Support">
    <div class="c-page-header-text">
    	<h1 class="text-uppercase dash-trailing">Support Monthly</h1>
    </div><!-- end c-page-header-content -->
</div><!-- end c-page-header -->

<div class="white-bg">
	<div class="container">
	<div class="content-section">
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
				<div id="wufoo-s3aoozl14cyfxf">
	              Fill out my <a href="https://missionsme.wufoo.com/forms/s3aoozl14cyfxf">online form</a>.
	            </div>
	              <script type="text/javascript">var s3aoozl14cyfxf;(function(d, t) {
	              var s = d.createElement(t), options = {
	              'userName':'missionsme',
	              'formHash':'s3aoozl14cyfxf',
	              'autoResize':true,
	              'height':'1419',
	              'async':true,
	              'host':'wufoo.com',
	              'header':'show',
	              'ssl':true};
	              s.src = ('https:' == d.location.protocol ? 'https://' : 'http://') + 'wufoo.com/scripts/embed/form.js';
	              s.onload = s.onreadystatechange = function() {
	              var rs = this.readyState; if (rs) if (rs != 'complete') if (rs != 'loaded') return;
	              try { s3aoozl14cyfxf = new WufooForm();s3aoozl14cyfxf.initialize(options);s3aoozl14cyfxf.display(); } catch (e) {}};
	              var scr = d.getElementsByTagName(t)[0], par = scr.parentNode; par.insertBefore(s, scr);
	              })(document, 'script');</script>
			</div><!-- end col -->
		</div><!-- end row -->
	</div>
	</div><!-- end container -->
</div><!-- end dark-bg-primary -->
@endsection