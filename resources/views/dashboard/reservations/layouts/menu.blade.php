<div class="panel panel-default">
	<div class="panel-heading">
		<div style="display:inline-block;">
			<img class="img-circle img-sm" src="../../../images/jasmine-prof-pic.jpg">
		</div>
		<div style="display:inline-block;vertical-align:middle;margin:0 0 0 10px;">
			<label style="margin-bottom:0px;">Your Trip Rep</label>
			<h5 style="margin:3px 0 6px;">Jasmine F</h5>
			<p style="font-size:10px;margin-top:3px;"><i class="fa fa-phone"></i> <a href="tel:5555555555">555-555-5555</a> / <i class="fa fa-envelope"></i> <a href="mailto:jasminef@missions.me">jasminef@missions.me</a></p>
		</div>
	</div><!-- end panel-heading -->
	<ul class="nav nav-pills nav-stacked">
		@foreach($links as $link)
		<li class="{{ $tab == $link['url'] ? 'active' : '' }}">
			<a href="{{ url('dashboard/reservations', [$reservation->id, $link['url']]) }}">
				{{ $link['label'] }}
			</a>
		</li>
		@endforeach
	</ul>
</div><!-- end panel -->