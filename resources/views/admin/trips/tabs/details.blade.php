@component('panel')
    @slot('title')
        <div class="row">
            <div class="col-xs-8">
                <h5>Registration Settings</h5>
            </div>
            <div class="col-xs-4 text-right">
            </div>
        </div>
    @endslot
    @component('list-group', ['data' => [
        'Current Status' => '<strong>'.ucfirst($trip->status).'</strong>',
        'Starting Cost' => '$'.$trip->startingCostInDollars(),
        'Visibility' => ($trip->public ? 'Public' : 'Private'),
        'Spots Remaining' => $trip->spots,
        'Total Reservations' => $trip->reservations()->count(),
        'Registration Closes' => ($trip->closed_at ? $trip->closed_at->format('F d, Y h:i a') : null)
    ]])
    @endcomponent
@endcomponent

@component('panel')
    @slot('title')
        <h5>Details</h5>
    @endslot
    @component('list-group', ['data' => [
        'Campaign' => '<a href="'.url('admin/campaigns/'.$trip->campaign->id).'">'.$trip->campaign->name.'</a>',
        'Country' => country($trip->country_code),
        'Organization' => '<a href="'.url('admin/groups/'.$trip->group->id).'">'.$trip->group->name.'</a>',
        'Type' => '<strong>'.ucfirst($trip->type).'</strong>',
        'Start Date' => $trip->started_at->format('F d, Y'),
        'End Date' => $trip->ended_at->format('F d, Y'),
        'Difficulty' => $trip->difficulty,
        'Default Trip Rep' => '<a href="'.url('admin/representatives/'.$trip->rep->id).'">'.$trip->rep->name.'</a>',
        'Default Companion Limit' => $trip->companion_limit,
        'Created' => '<datetime-formatted value="'.$trip->created_at->toIso8601String().'" />',
        'Last Updated' => '<datetime-formatted value="'.$trip->updated_at->toIso8601String().'" />'
    ]])
    @endcomponent
@endcomponent

<div class="row">
    <div class="col-xs-12 text-right">
        <div class="btn-group dropup">
        <a type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <strong class="text-primary"><i class="fa fa-cog"></i> Manage</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-right">
            @can('update', $trip)
            <li>
                <a href="{{ url('/admin/trips/' . $trip->id . '/edit')}}">Edit</a>
            </li>
            @endcan
            @can('create', \App\Models\v1\Reservation::class)
            <li>
                <a data-toggle="modal"
                    data-target="#addReservationModal"
                    data-backdrop="static">
                    Create Reservation
                </a>
            </li>
            @endcan
            @can('create', $trip)
            <li>
                <a data-toggle="modal" data-target="#duplicationModal">Duplicate</a>
            </li>
            @endcan
            @can('delete', $trip)
            <li role="separator" class="divider"></li>
            <li>
                <a data-toggle="modal" data-target="#deleteConfirmationModal">Delete</a>
            </li>
            @endcan
        </ul>
        </div>
    </div>
</div>