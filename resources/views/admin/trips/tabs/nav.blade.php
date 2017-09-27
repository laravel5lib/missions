 <div class="panel panel-default">
    <ul class="nav nav-pills nav-stacked">
        <li class="{{ $tab === 'details' || $tab === 'reservations' ? 'active' : '' }}"><a href="{{ url('admin/trips/' . $trip->id) . '/details' }}">Details</a></li>
        <li class="{{ $tab === 'description' ? 'active' : '' }}"><a href="{{ url('admin/trips/' . $trip->id) . '/description' }}">Description</a></li>

        @can('view', \App\Models\v1\Cost::class)
            <li class="{{ $tab === 'pricing' ? 'active' : '' }}">
                <a href="{{ url('admin/trips/' . $trip->id) . '/pricing' }}">Pricing</a>
            </li>
        @endcan

        @can('view', \App\Models\v1\Requirement::class)
            <li class="{{ $tab === 'requirements' ? 'active' : '' }}">
                <a href="{{ url('admin/trips/' . $trip->id) . '/requirements' }}">Requirements</a>
            </li>
        @endcan

        <li class="{{ $tab === 'deadlines' ? 'active' : '' }}">
            <a href="{{ url('admin/trips/' . $trip->id) . '/deadlines' }}">Deadlines</a>
        </li>

        <li class="{{ $tab === 'facilitators' ? 'active' : '' }}">
            <a href="{{ url('admin/trips/' . $trip->id) . '/facilitators' }}">Facilitators</a>
        </li>

        @can('view', \App\Models\v1\Todo::class)
            <li class="{{ $tab === 'todos' ? 'active' : '' }}">
                <a href="{{ url('admin/trips/' . $trip->id) . '/todos' }}">Todos</a>
            </li>
        @endcan

        @can('view', \App\Models\v1\Note::class)
        <li class="{{ $tab === 'notes' ? 'active' : '' }}">
            <a href="{{ url('admin/trips/' . $trip->id) . '/notes' }}">Notes</a>
        </li>
        @endcan

        <li class="{{ $tab === 'resources' ? 'active' : '' }}">
            <a href="{{ url('admin/trips/' . $trip->id) . '/resources' }}">Resources</a>
        </li>
    </ul>
</div>
