@extends('admin.campaigns.show')

@section('tab')
<div>
    <rooming-wizard user-id="{{ Auth::user()->id }}" campaign-id="{{ $campaign->id }}"></rooming-wizard>
</div>
@endsection