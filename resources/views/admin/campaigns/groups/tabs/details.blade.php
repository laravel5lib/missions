@extends('admin.campaigns.groups.show')

@section('tab')

@component('panel')
    @slot('body')
    <div class="row">
        <div class="col-sm-3">
            <h4 class="text-primary">{{ $group->status }}</h4>
            <p class="small text-muted">Status</p>
        </div>
        <div class="col-sm-4">
            <h4>0</h4>
            <p class="small text-muted">Trips</p>
        </div>
        <div class="col-sm-5">
            <h4>0</h4>
            <p class="small text-muted">Reservations</p>
        </div>
    </div>
    @endslot
@endcomponent

@component('panel')
    @slot('title')
        <div class="row">
            <div class="col-xs-6">
                <h5>Group Details</h5>
            </div>
            <div class="col-xs-6 text-right">
                <a href="#" class="btn btn-default btn-sm">Edit</a>
            </div>
        </div>
    @endslot
    @component('list-group', [
        'data' => array_merge(['Status' => $group->status], ($group->meta ?: []))
    ])
    @endcomponent
@endcomponent

@component('panel')
    @slot('title')
        <h5>Organization</h5>
    @endslot
    @component('list-group', ['data' => [
        'Name' => $group->organization->name,
        'Type' => $group->organization->type,
        'Status' => ($group->organization->public ? 'Public' : 'Private'),
        'Email' => $group->organization->email,
        'Primary Phone' => $group->organization->phone_one,
        'Secondary Phone' => $group->organization->phone_two,
        'Timezone' => $group->organization->timezone,
        'Address' => $group->organization->address.'<br />'.$group->organization->address_two.'<br />'.$group->organization->city.', '.$group->organization->state.' '.$group->organization->zip.'<br />'.country($group->organization->country_code),
        'Description' => $group->organization->description,
        'Created' => '<datetime-formatted value="'.$group->organization->created_at->toIso8601String().'" />',
        'Last Updated' => '<datetime-formatted value="'.$group->organization->updated_at->toIso8601String().'" />'
    ]])
    @endcomponent
@endcomponent

@endsection