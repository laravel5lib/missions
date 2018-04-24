<ul class="nav nav-pills nav-stacked">
    <li class="{{ $tab === 'details' || $tab === 'reservations' ? 'active' : '' }}">
        <a href="{{ url('admin/trips/' . $trip->id) . '/details' }}">Overview</a>
    </li>

    @can('view', \App\Models\v1\Cost::class)
        <li class="{{ $tab === 'pricing' ? 'active' : '' }}">
            <a href="{{ url('admin/trips/' . $trip->id) . '/pricing' }}">
                Pricing
                @if (! $trip->priceables()->count())
                <sup class="text-danger"><i class="fa fa-exclamation-circle"></i></sup>
            @endif
            </a>
        </li>
    @endcan

    <li class="{{ $tab === 'description' ? 'active' : '' }}">
        <a href="{{ url('admin/trips/' . $trip->id) . '/description' }}">
            Public Page
            @if (! $trip->description)
                <sup class="text-danger"><i class="fa fa-exclamation-circle"></i></sup>
            @endif
        </a>
    </li>

    @can('view', \App\Models\v1\Requirement::class)
        <li class="{{ $tab === 'requirements' ? 'active' : '' }}">
            <a href="{{ url('admin/trips/' . $trip->id) . '/requirements' }}">
                Requirements
                @if (! $trip->requirements()->count())
                    <sup class="text-danger"><i class="fa fa-exclamation-circle"></i></sup>
                @endif
            </a>
        </li>
    @endcan

    @can('view', \App\Models\v1\Todo::class)
        <li class="{{ $tab === 'todos' ? 'active' : '' }}">
            <a href="{{ url('admin/trips/' . $trip->id) . '/todos' }}">Todos <sup class="text-primary">{{ count($trip->todos) }}<sup></a>
        </li>
    @endcan

    @can('view', \App\Models\v1\Note::class)
    <li class="{{ $tab === 'notes' ? 'active' : '' }}">
        <a href="{{ url('admin/trips/' . $trip->id) . '/notes' }}">Notes <sup class="text-primary">{{ $trip->notes()->count() }}<sup></a>
    </li>
    @endcan
</ul>
