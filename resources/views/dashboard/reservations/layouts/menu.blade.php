	<label>Menu</label>
	<ul class="nav nav-pills nav-stacked">
		@foreach($links as $link)
		<li class="{{ $tab == $link['url'] ? 'active' : '' }}">
			<a href="{{ url('dashboard/reservations', [$reservation->id, $link['url']]) }}">
				{{ $link['label'] }}
			</a>
		</li>
		@endforeach
	</ul>