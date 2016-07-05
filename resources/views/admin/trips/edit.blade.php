@extends('admin.layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <trip-edit trip-id="{{ $campaignId }}"></trip-edit>
            </div>
        </div>
    </div>
@endsection