@extends('site.layouts.default')

@section('content')
    <hr class="divider inv">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>Referral Request from {{ $referral->user->name }}</h5>
                    </div>
                    <div class="panel-body">
                        <referral-response id="{{ $referral->id }}"></referral-response>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection