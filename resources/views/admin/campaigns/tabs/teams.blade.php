@extends('admin.campaigns.show')

@section('tab')
<div>
    <team-manager campaign-id="{{ $campaign->id }}"></team-manager>
</div>
@endsection