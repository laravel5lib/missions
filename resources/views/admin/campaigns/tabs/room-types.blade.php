@extends('admin.campaigns.show')

@section('tab')
<div>
    <rooming-type-manager campaign-id="{{ $campaign->id }}"></rooming-type-manager>
</div>
@endsection