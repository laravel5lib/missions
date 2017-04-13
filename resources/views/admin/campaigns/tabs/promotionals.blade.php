@extends('admin.campaigns.show')

@section('tab')
    <promotionals promoter-type="campaigns" promoter-id="{{ $campaign->id}}"></promotionals>
@endsection