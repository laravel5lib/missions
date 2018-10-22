@extends('admin.campaigns.groups.show')

@section('tab')

@component('panel')
    @slot('body')
    <div class="row">
        <div class="col-sm-6">
            <h4 class="text-primary">{{ $group->status }}</h4>
            <p class="small text-muted">Status</p>
        </div>
        <div class="col-sm-3">
            <h4>{{ $group->tripsCount() }}</h4>
            <p class="small text-muted">Trips</p>
        </div>
        <div class="col-sm-3">
            <h4>{{ $group->reservationsCount() }}</h4>
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
                <a href="{{ url('admin/campaign-groups/'.$group->uuid.'/edit') }}" class="btn btn-default btn-sm">Edit</a>
            </div>
        </div>
    @endslot
    @component('list-group', [
        'data' => array_merge(['Status' => $group->status, 'Commitment' => $group->commitment], ($meta ?: []))
    ])
    @endcomponent
@endcomponent

@component('panel')
    @slot('title')
        <h5>Organization</h5>
    @endslot
    @component('list-group', ['data' => [
        'Name' => '<a href="/admin/organizations/'.$group->group_id.'"><strong>'.$group->organization->name.'</strong></a>',
        'Type' => $group->organization->type,
        'Status' => ($group->organization->public ? 'Public' : 'Private'),
        'Email' => '<a href="mailto:'.$group->organization->email.'"><strong>'.$group->organization->email.'</strong></a>',
        'Primary Phone' => '<a href="tel:'.$group->organization->phone_one.'"><strong>'.$group->organization->phone_one.'</strong></a>',
        'Secondary Phone' => '<a href="tel:'.$group->organization->phone_two.'"><strong>'.$group->organization->phone_two.'</strong></a>',
        'Timezone' => $group->organization->timezone,
        'Address' => $group->organization->address.'<br />'.$group->organization->address_two.'<br />'.$group->organization->city.', '.$group->organization->state.' '.$group->organization->zip.'<br />'.country($group->organization->country_code),
        'Description' => $group->organization->description,
        'Created' => '<datetime-formatted value="'.$group->organization->created_at->toIso8601String().'" />',
        'Last Updated' => '<datetime-formatted value="'.$group->organization->updated_at->toIso8601String().'" />'
    ]])
    @endcomponent
@endcomponent

@can('delete', $group->organization)
    @component('panel')
        @slot('title')
            <h5>Remove Group</h5>
        @endslot
        @slot('body')
            <div class="alert alert-warning">
                <div class="row">
                    <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                    <div class="col-xs-11">USE CAUTION! This is a destructive action that cannot be undone. This will disassociate the group from the campaign but also delete trips and drop reservations.</div>
                </div>
            </div>
            <delete-form url="campaigns/{{ $group->campaign_id }}/groups/{{ $group->group_id }}" 
                            redirect="/admin/campaigns/{{ $group->campaign_id }}/groups"
                            label="Enter the group name to remove it"
                            button="Remove"
                            match-value="{{ $group->organization->name }}">
            </delete-form>
        @endslot
    @endcomponent
@endcan

@endsection