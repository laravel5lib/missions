@extends('admin.layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <campaign-edit campaign-id="{{ $campaignId }}"></campaign-edit>
            </div>
        </div>
    </div>
@endsection