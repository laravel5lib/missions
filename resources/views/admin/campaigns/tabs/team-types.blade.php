@extends('admin.campaigns.show')

@section('tab')
<div>
    <team-type-manager campaign-id="{{ $campaign->id }}"></team-type-manager>
</div>
@endsection