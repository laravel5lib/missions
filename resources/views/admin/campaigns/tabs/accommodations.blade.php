@extends('admin.campaigns.show')

@section('tab')
<div>
    <rooming-accommodations campaign-id="{{ $campaign->id }}" ></rooming-accommodations>
</div>
@endsection