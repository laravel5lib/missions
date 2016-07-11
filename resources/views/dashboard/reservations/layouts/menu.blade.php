<a href="{{ url('dashboard/reservations') }}" class="btn btn-block btn-primary"><span class="fa fa-chevron-left"></span> My Reservations</a>
<ul class="nav nav-tabs nav-stacked">
    <li role="presentation" class="{{ $active == 'details' ? 'active' : '' }}"><a href="{{ url('dashboard/reservations', [$reservation->id]) }}">Details</a></li>
    <li role="presentation" class="{{ $active == 'requirements' ? 'active' : '' }}"><a href="{{ url('dashboard/reservations', [$reservation->id, 'requirements']) }}">Travel Documents</a></li>
    <li role="presentation" class="{{ $active == 'donations' ? 'active' : '' }}"><a href="{{ url('dashboard/reservations', [$reservation->id, 'donations']) }}">Donations</a></li>
    <li role="presentation" class="{{ $active == 'funding' ? 'active' : '' }}"><a href="{{ url('dashboard/reservations', [$reservation->id, 'funding']) }}">Funding</a></li>
    <li role="presentation" class="{{ $active == 'fundraisers' ? 'active' : '' }}"><a href="{{ url('dashboard/reservations', [$reservation->id, 'fundraisers']) }}">Fundraisers</a></li>
    <li role="presentation" class="{{ $active == 'deadlines' ? 'active' : '' }}"><a href="{{ url('dashboard/reservations', [$reservation->id, 'deadlines']) }}">Due Dates</a></li>
</ul>