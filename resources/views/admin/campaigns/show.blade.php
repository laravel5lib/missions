@extends('admin.layouts.default')

@section('content')

<div class="container">
  <admin-campaign-details campaign="{{ json_encode($campaign) }}" campaign-id="{{ $campaign->id }}"></admin-campaign-details>
</div>

@endsection