@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3 class="hidden-xs">User Referrals <small>&middot; {{ $referral->name }}</small></h3>
                    <h3 class="visible-xs text-center">User Referrals<br><small>{{ $referral->name }}</small></h3>
                </div>
                <div class="col-sm-4 text-right hidden-xs">
                    <hr class="divider inv sm">
                    <div class="btn-group">
                        <a onclick="window.history.back()" class="btn btn-primary-darker">
                            <span class="fa fa-chevron-left icon-left"></span> Back
                        </a>
                        {{-- <a href="{{ url('admin/records/referrals/' . $referral->id . '/edit') }}" class="btn btn-primary">
                            Edit
                        </a> --}}
                    </div>
                </div>
                <div class="col-sm-4 text-center visible-xs">
                    <div class="btn-group">
                        <a onclick="window.history.back()" class="btn btn-primary-darker">
                            <span class="fa fa-chevron-left icon-left"></span>
                        </a>
                        <a href="{{ url('admin/records/referrals/' . $referral->id . '/edit') }}" class="btn btn-primary">
                            Edit
                        </a>
                    </div>
                    <hr class="divider inv sm">
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv">
    @include('_referral', $referral)

    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>Response</h5>
                    </div>
                    <div class="panel-body">
                        @foreach($referral->response as $response)
                            <label>{{ $response['q'] }}</label>
                            <p>{{ $response['a'] ? $response['a'] : '...' }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <notes type="referrals" id="{{ $referral->id }}" :can-modify="1"></notes>
            </div>
        </div>
    </div>
@endsection