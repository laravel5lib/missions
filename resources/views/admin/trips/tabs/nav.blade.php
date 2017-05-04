 <div class="panel panel-default">
    <ul class="nav nav-pills nav-stacked">
        <li class="{{ $tab === 'details' || $tab === 'reservations' ? 'active' : '' }}"><a href="{{ url('admin/trips/' . $trip->id) . '/details' }}">Details</a></li>
        <li class="{{ $tab === 'description' ? 'active' : '' }}"><a href="{{ url('admin/trips/' . $trip->id) . '/description' }}">Description</a></li>
        <li class="{{ $tab === 'pricing' ? 'active' : '' }}"><a href="{{ url('admin/trips/' . $trip->id) . '/pricing' }}">Pricing</a></li>
        <li class="{{ $tab === 'requirements' ? 'active' : '' }}"><a href="{{ url('admin/trips/' . $trip->id) . '/requirements' }}">Requirements</a></li>
        <li class="{{ $tab === 'deadlines' ? 'active' : '' }}"><a href="{{ url('admin/trips/' . $trip->id) . '/deadlines' }}">Deadlines</a></li>
        <li class="{{ $tab === 'facilitators' ? 'active' : '' }}"><a href="{{ url('admin/trips/' . $trip->id) . '/facilitators' }}">Facilitators</a></li>
        <li class="{{ $tab === 'todos' ? 'active' : '' }}"><a href="{{ url('admin/trips/' . $trip->id) . '/todos' }}">Todos</a></li>
        <li class="{{ $tab === 'notes' ? 'active' : '' }}"><a href="{{ url('admin/trips/' . $trip->id) . '/notes' }}">Notes</a></li>
        <li class="{{ $tab === 'resources' ? 'active' : '' }}"><a href="{{ url('admin/trips/' . $trip->id) . '/resources' }}">Resources</a></li>
    </ul>
</div>
