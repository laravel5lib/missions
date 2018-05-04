@extends('admin.campaigns.show')

@section('tab')

<group-manager campaign-id="{{ $campaign->id }}"></group-manager>

@endsection