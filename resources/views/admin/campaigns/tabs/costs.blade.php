@extends('admin.campaigns.show')

@section('tab')

<campaign-cost-manager campaign-id="{{ $campaign->id }}" />

@endsection