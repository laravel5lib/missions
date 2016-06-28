@extends('admin.layouts.default')

@section('content')

<div class="container">
    <admin-campaign-details campaign-id="{{ $campaignId }}"></admin-campaign-details>
</div>

@endsection