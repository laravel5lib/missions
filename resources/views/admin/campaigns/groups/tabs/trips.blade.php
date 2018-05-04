@extends('admin.campaigns.groups.show')

@section('tab')

<trip-manager url="/trips?groups[]={{ $group->group_id }}&campaign={{ $group->campaign_id }}" />

@endsection