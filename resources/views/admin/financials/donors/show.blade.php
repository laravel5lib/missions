@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3>{{ $donor->name }} <small>&middot; Donor</small></h3>
                </div>
                <div class="col-sm-4 text-right">
                    <hr class="divider inv sm">
                    <a href="{{ url('admin/donors/'. $donor->id .'/edit') }}" class="btn btn-primary">
                        <i class="fa fa-pencil"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-header">Details</h5>
                    </div>
                    <div class="panel-body">
                        <label>Donor Name</label>
                        <p>{{ $donor->name }}</p>
                        @unless(! $donor->company)
                        <label>Company/Organization</label>
                        <p>{{ $donor->company }}</p>
                        @endunless
                        <label>Account Type</label>
                        <p>{{ $donor->account_type ? str_singular(ucwords($donor->account_type)) : 'Guest' }}</p>
                        <label>Stripe Customer ID</label>
                        <p>{{ $donor->customer_id or 'n/a' }}</p>
                        <label>Email</label>
                        <p>{{ $donor->email or 'n/a' }}</p>
                        <label>Phone</label>
                        <p>{{ $donor->phone or 'n/a' }}</p>
                        <label>Address</label>
                        <p>
                            {{ $donor->address ?  $donor->address . '<br />' : '' }}
                            {{ $donor->city ? $donor->city . ', ' : '' }}
                            {{ $donor->state }}
                            {{ $donor->zip }}
                        </p>
                        <label>Country</label>
                        <p>{{ country($donor->country_code) }}</p>
                        <label>Last Update</label>
                        <p>{{ $donor->updated_at->format('F j, Y h:i a') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <h5>Transactions</h5>
                <admin-transactions-list donor="{{ $donor->id }}"
                                         storage-name="AdminDonorTransactionsConfig">
                </admin-transactions-list>
            </div>
        </div>
    </div>
@stop