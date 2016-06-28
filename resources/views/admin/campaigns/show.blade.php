@extends('admin.layouts.default')

@section('content')

<div class="container">
  <admin-campaign-details campaign-id="{{ $campaign->id }}"></admin-campaign-details>
</div>

@endsection