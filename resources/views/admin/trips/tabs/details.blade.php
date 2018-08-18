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
        'Current Status' => ucfirst($trip->status),
        'Visibility' => ($trip->public ? 'Public' : 'Private'),
        'Spots Remaining' => $trip->spots,
        'Total Reservations' => $trip->reservations()->count(),
        'Registration Closes' => ($trip->closed_at ? $trip->closed_at->format('F d, Y h:i a') : null),
        'Roles Available' => function() use($trip) {
            foreach($trip->team_roles as $role) {
                echo '<span class="label label-filter">'.teamRole($role).'</span> ';
            }
        }
    ]])
    @endcomponent
@endcomponent

@component('panel')
    @slot('title')
        <div class="row">
            <div class="col-xs-8">
                <h5>Details</h5>
            </div>
            <div class="col-xs-4 text-right">
                <a class="btn btn-sm btn-default" href="{{ url('/admin/trips/' . $trip->id . '/edit')}}">
                    <strong><i class="fa fa-pencil"></i> Edit</strong>
                </a>
            </div>
        </div>
    @endslot
    @component('list-group', ['data' => [
        'Campaign' => '<a href="'.url('admin/campaigns/'.$trip->campaign->id).'"><strong>'.$trip->campaign->name.'</strong></a>',
        'Country' => country($trip->country_code),
        'Organization' => '<a href="'.url('admin/groups/'.$trip->group->id).'"><strong>'.$trip->group->name.'</strong></a>',
        'Type' => ucfirst($trip->type),
        'Tags' => function() use($trip) {
            foreach($trip->tags as $tag) {
                echo '<span class="label label-filter">'.ucwords($tag->name).'</span>';
            }
        },
        'Start Date' => $trip->started_at->format('F d, Y'),
        'End Date' => $trip->ended_at->format('F d, Y'),
        'Difficulty' => $trip->difficulty,
        'Ideal for' => function() use($trip) {
            foreach($trip->prospects as $prospect) {
                echo '<span class="label label-filter">'.ucwords($prospect).'</span> ';
            }
        },
        'Default Trip Rep' => '<a href="'.url('admin/representatives/'.optional($trip->rep)->id).'"><strong>'.optional($trip->rep)->name.'</strong></a>',
        'Default Companion Limit' => $trip->companion_limit,
        'Created' => '<datetime-formatted value="'.$trip->created_at->toIso8601String().'" />',
        'Last Updated' => '<datetime-formatted value="'.$trip->updated_at->toIso8601String().'" />'
    ]])
    @endcomponent
@endcomponent


@component('panel')
    @slot('title')
        <h5>Delete Trip</h5>
    @endslot
    @slot('body')
        <div class="alert alert-warning">
            <div class="row">
                <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                <div class="col-xs-11">USE CAUTION! This is a destructive action that cannot be undone. This will delete the trip and drop all it's reservations.</div>
            </div>
        </div>
        <delete-form url="trips/{{ $trip->id }}" 
                    redirect="/admin/campaign-groups/{{ $group->uuid }}/trips" 
                    label="Enter the trip type to delete it" 
                    match-value="{{ ucfirst($trip->type) }}">
        </delete-form>
    @endslot
@endcomponent