@extends('admin.campaigns.show')

@section('tab')
<div>
    <regions-accommodations campaign-id="{{ $campaign->id }}" ></regions-accommodations>
</div>
@endsection