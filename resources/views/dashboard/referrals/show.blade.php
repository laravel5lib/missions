@extends('dashboard.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3>My Referrals <small>&middot; {{ $referral->name }}</small></h3>
                </div>
                <div class="col-sm-4 text-right">
                    <hr class="divider inv sm">
                    <div class="btn-group">
                        <a href="{{ url('dashboard/records/referrals') }}" class="btn btn-primary-darker">
                            <span class="fa fa-chevron-left icon-left"></span>
                        </a>
                        <a href="{{ url('dashboard/records/referrals/' . $referral->id . '/edit') }}" class="btn btn-primary">
                            <i class="fa fa-edit icon-left"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>Details</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Type</label>
                                        <p>{{ ucwords($referral->type) }}</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Name</label>
                                        <p>{{ $referral->name }}</p>
                                    </div>
                                </div>
                                <hr class="divider">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Referral Name</label>
                                        <p>{{ $referral->referral_name }}</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Referral Email</label>
                                        <p>{{ $referral->referral_email }}</p>
                                    </div>
                                </div>
                                <hr class="divider">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Referral Phone</label>
                                        <p>{{ $referral->referral_phone }}</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Status</label>
                                        <p>{{ ucfirst($referral->status) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="panel panel-default panel-body text-center">
                                    <label>Managing User</label>
                                    <p>{{ $referral->user->name }}</p>
                                    <label>Email</label>
                                    <p>{{ $referral->user->email }}</p>
                                    <label>Phone</label>
                                    <p>{{ $referral->user->phone_one ? $referral->user->phone_one : $referral->user->phone_two }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection