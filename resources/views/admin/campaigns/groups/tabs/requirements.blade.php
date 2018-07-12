@extends('admin.campaigns.groups.show')

@section('tab')

<requirements-manager 
    inheritable-id="{{ $group->campaign_id }}" 
    inheritable-type="campaigns" 
    requester-type="campaign-groups" 
    requester-id="{{ $group->uuid }}"
></requirements-manager>

@endsection