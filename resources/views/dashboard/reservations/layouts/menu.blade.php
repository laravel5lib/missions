<div class="panel panel-default">
	@if($rep)
	<div class="panel-heading">
		<div style="display:inline-block;">
			<img class="img-circle img-sm" src="{{ $rep->avatar_url }}" width="50" height="50">
		</div>
		<div style="display:inline-block;vertical-align:middle;margin:0 0 0 10px;">
			<label style="margin-bottom:0px;font-size:10px;">Your Trip Rep</label>
			<h5 style="margin:3px 0 6px;">{{ $rep->name }}</h5>
			<p style="font-size:10px;margin-top:3px;"><i class="fa fa-phone"></i> <a href="tel:{{ $rep->phone }}">{{ $rep->phone }}</a> / <i class="fa fa-envelope"></i> <a href="mailto:{{ $rep->email }}">{{ $rep->email }}</a></p>
		</div>
	</div><!-- end panel-heading -->
	@endif
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