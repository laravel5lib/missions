<div class="col-xs-12 col-sm-4 col-md-3">
    <div class="panel panel-default">
        <ul class="nav nav-pills nav-stacked">
            <li class="{{ $tab === 'details' ? 'active' : '' }}"><a href="{{ url('admin/trips/' . $trip->id) . '/details' }}">Details</a></li>
            <li class="{{ $tab === 'registration' ? 'active' : '' }}"><a href="{{ url('admin/trips/' . $trip->id) . '/registration' }}">Registration</a></li>
            <li class="{{ $tab === 'pricing' ? 'active' : '' }}"><a href="{{ url('admin/trips/' . $trip->id) . '/pricing' }}">Pricing</a></li>
            <li class="{{ $tab === 'requirements' ? 'active' : '' }}"><a href="{{ url('admin/trips/' . $trip->id) . '/requirements' }}">Requirements</a></li>
            <li class="{{ $tab === 'deadlines' ? 'active' : '' }}"><a href="{{ url('admin/trips/' . $trip->id) . '/deadlines' }}">Deadlines</a></li>
            <li class="{{ $tab === 'todos' ? 'active' : '' }}"><a href="{{ url('admin/trips/' . $trip->id) . '/todos' }}">Todos</a></li>
            <li class="{{ $tab === 'notes' ? 'active' : '' }}"><a href="{{ url('admin/trips/' . $trip->id) . '/notes' }}">Notes</a></li>
        </ul>
    </div>
</div>
