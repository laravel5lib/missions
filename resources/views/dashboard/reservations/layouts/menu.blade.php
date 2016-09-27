<div class="panel panel-default">
	<div class="panel-heading">
		<a href="{{ url('dashboard/reservations') }}" class="btn btn-block btn-default"><span class="fa fa-chevron-left icon-left"></span> My Reservations</a>
	</div>
	<ul class="list-group">
		<a class="list-group-item {{ $active == 'details' ? 'active' : '' }}" href="{{ url('dashboard/reservations', [$reservation->id]) }}">Details</a>
	    <a class="list-group-item {{ $active == 'requirements' ? 'active' : '' }}" href="{{ url('dashboard/reservations', [$reservation->id, 'requirements']) }}">Travel Documents</a>
		{{--    <a class="list-group-item {{ $active == 'donations' ? 'active' : '' }}" href="{{ url('dashboard/reservations', [$reservation->id, 'donations']) }}">Donations</a>--}}
		{{--    <a class="list-group-item {{ $active == 'fundraisers' ? 'active' : '' }}" href="{{ url('dashboard/reservations', [$reservation->id, 'fundraisers']) }}">Fundraisers</a>--}}
	    <a class="list-group-item {{ $active == 'funding' ? 'active' : '' }}" href="{{ url('dashboard/reservations', [$reservation->id, 'funding']) }}">Funding</a>
	    <a class="list-group-item {{ $active == 'deadlines' ? 'active' : '' }}" href="{{ url('dashboard/reservations', [$reservation->id, 'deadlines']) }}">Due Dates</a>
	</ul>
</div><!-- end panel -->