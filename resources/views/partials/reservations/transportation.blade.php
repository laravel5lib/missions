<table class="table table-responsive">
    <thead>
    <tr>
        <th>Transport</th>
        <th>Departure</th>
        <th>Arrival</th>
    </tr>
    </thead>
    <tbody>
    @forelse($reservation->transports()->whereNotNull('designation')->get() as $transport)
        <tr>
            <td class="col-xs-4">
                {{ ucwords($transport->name) }}
                <code>{{ strtoupper($transport->vessel_no) }}</code>
                <br />
                <small>
                    {{ ucwords($transport->type) }}
                    <span class="label label-info">{{ $transport->domestic ? 'Domestic' : 'International' }}</span>
                    <span class="label label-primary">{{ ucwords($transport->designation) }}</span>
                </small>
            </td>
            <td class="col-xs-4">
                @if($transport->departureHub)
                    {{ $transport->departureHub->call_sign }} <code>{{ $transport->depart_at->format('F j, h:i a') }}</code>
                    <br />
                    <small>{{ $transport->departureHub->name }}, {{ country($transport->departureHub->country_code) }}</small>
                @else
                    N/A
                @endif
            </td>
            <td class="col-xs-4">
                @if($transport->arrivalHub)
                    {{ $transport->arrivalHub->call_sign }} <code>{{ $transport->arrive_at->format('F j, h:i a') }}</code>
                    <br />
                    <small>{{ $transport->arrivalHub->name }}, {{ country($transport->arrivalHub->country_code) }}</small>
                @else
                    N/A
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="3" class="text-center">No assigned transportation.</td>
        </tr>
    @endforelse
    </tbody>
</table>