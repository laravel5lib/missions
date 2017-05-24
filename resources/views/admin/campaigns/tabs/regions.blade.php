@extends('admin.campaigns.show')

@section('tab')
<div>
    <regions-manager campaign-id="{{ $campaign->id }}"></regions-manager>
</div>
@endsection