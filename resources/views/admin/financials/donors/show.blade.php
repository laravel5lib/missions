@extends('layouts.admin')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3>{{ $donor->name }} <small>&middot; Donor</small></h3>
                </div>
                <div class="col-sm-4 text-right">
                    <hr class="divider inv sm">
                    <div class="btn-group">
                        <a href="{{ url('admin/donors') }}" class="btn btn-primary-darker"><span class="fa fa-chevron-left icon-left"></span></a>
                        @can('update', $donor)
                        <a href="{{ url('admin/donors/'. $donor->id .'/edit') }}" class="btn btn-primary">
                            Edit
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-header">Details</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Donor Name</label>
                                <p>{{ $donor->name }}</p>
                            </div>
                            @unless(! $donor->company)
                            <div class="col-sm-6">
                                <label>Company/Organization</label>
                                <p>{{ $donor->company }}</p>
                            </div>
                            @endunless
                        </div>
                        <hr class="divider">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Account Type</label>
                                <p>{{ $donor->account_type ? str_singular(ucwords($donor->account_type)) : 'Guest' }}</p>
                            </div>
                            <div class="col-sm-6">
                                <label>Stripe Customer ID</label>
                                <p>{{ $donor->customer_id or 'n/a' }}</p>
                            </div>
                        </div>
                        <hr class="divider">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Email</label>
                                <p>{{ $donor->email or 'n/a' }}</p>
                            </div>
                            <div class="col-sm-6">
                                <label>Phone</label>
                                <p>{{ $donor->phone or 'n/a' }}</p>
                            </div>
                        </div>
                        <hr class="divider">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Address</label>
                                <p>
                                    {!! $donor->address ?  $donor->address . '<br />' : '' !!}
                                    {{ $donor->city ? $donor->city . ', ' : '' }}
                                    {{ $donor->state }}
                                    {{ $donor->zip }}
                                </p>
                            </div>
                            <div class="col-sm-6">
                                <label>Country</label>
                                <p>{{ country($donor->country_code) }}</p>
                            </div>
                        </div>
                        <hr class="divider">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Last Update</label>
                                <p>{{ $donor->updated_at->format('F j, Y h:i a') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @can('view', \App\Models\v1\Transaction::class)
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>Transactions</h5>
                    </div>
                    <div class="panel-body">
                        <admin-transactions-list donor="{{ $donor->id }}"
                                                 storage-name="AdminDonorTransactionsConfig">
                        </admin-transactions-list>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('view', \App\Models\v1\Note::class)
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <notes type="donors"
                       id="{{ $donor->id }}"
                       user_id="{{ auth()->user()->id }}"
                       :per_page="3">
                </notes>
            </div>
        </div>
        @endcan

    </div>
@stop