@extends('admin.campaigns.show')

@section('tab')
<div>
    <team-manager user-id="{{ auth()->user()->id }}" campaign-id="{{ $campaign->id }}"></team-manager>
</div>
@endsection