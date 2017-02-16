@extends('admin.layouts.default')

@section('content')
<div>
<admin-campaign-details campaign-id="{{ $campaignId }}"></admin-campaign-details>
</div>
@endsection