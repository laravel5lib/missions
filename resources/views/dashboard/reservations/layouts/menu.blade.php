<div class="panel panel-default">
	<ul class="nav nav-pills nav-stacked">
		@foreach($links as $link)
		<li class="{{ $tab == $link['url'] ? 'active' : '' }}">
			<a href="{{ url('dashboard/reservations', [$reservation->id, $link['url']]) }}">
				{{ $link['label'] }}
			</a>
		</li>
		@endforeach

		{{--<a class="list-group-item {{ $active == 'details' ? 'active' : '' }}" href="{{ url('dashboard/reservations', [$reservation->id]) }}">Details</a>--}}
	    {{--<a class="list-group-item {{ $active == 'requirements' ? 'active' : '' }}" href="{{ url('dashboard/reservations', [$reservation->id, 'requirements']) }}">Travel Documents</a>--}}
	    {{--<a class="list-group-item {{ $active == 'funding' ? 'active' : '' }}" href="{{ url('dashboard/reservations', [$reservation->id, 'funding']) }}">Funding</a>--}}
	    {{--<a class="list-group-item {{ $active == 'deadlines' ? 'active' : '' }}" href="{{ url('dashboard/reservations', [$reservation->id, 'deadlines']) }}">Due Dates</a>--}}
	</ul>
</div><!-- end panel -->