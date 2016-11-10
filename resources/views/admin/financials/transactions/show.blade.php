@extends('admin.layouts.default')
@inject('fund', 'App\Models\v1\Fund')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3>{{ ucwords($transaction->type) }} Details</h3>
                </div>
                <div class="col-sm-4">
                    <hr class="divider inv sm">
                    <a href="#" class="btn btn-primary pull-right">New <i class="fa fa-plus"></i></a>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-header">Details</h5>
                    </div>
                    <div class="panel-body">
                        <label>Amount</label>
                        @if($transaction->amount > 0)<h4 class="text-success">@else <h4 class="text-danger">@endif
                            $ {{ $transaction->amount }}
                        </h4>
                        <label>Description</label>
                        <p>{{ $transaction->description }}</p>
                        <label>Type</label>
                        <p>{{ str_singular(ucwords($transaction->type)) }}</p>
                        <label>Designation</label>
                        <p><a href="{{ url('/admin/funds/'. $transaction->fund->id) }}">{{ $transaction->fund->name }}</a></p>
                        <label>QuickBooks Class</label>
                        <p>{{ $transaction->fund->class }}</p>
                        <label>QuickBooks Item</label>
                        <p>{{ $transaction->fund->item }}</p>
                        <label>Created</label>
                        <p>{{ $transaction->created_at->format('F j, Y h:i a') }}</p>
                        <label>Last Updated</label>
                        <p>{{ $transaction->updated_at->format('F j, Y h:i a') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                @if($transaction->payment && $transaction->type == 'transfer')
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5 class="panel-header">Related Fund</h5>
                        </div>
                        <div class="panel-body">
                            <label>{{ $transaction->payment['label'] }}</label>
                            <p>
                                <a href="/admin/funds/{{ $transaction->payment['fund_id'] }}">
                                    {{ $fund->findOrFail($transaction->payment['fund_id'])->name }}
                                </a>
                            </p>
                        </div>
                    </div>
                @endif

                @if($transaction->payment && $transaction->type == 'donation')
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-header">Payment</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Type</label>
                                <p>{{ $transaction->payment['type'] or 'n/a' }}</p>
                                <label>Brand</label>
                                <p>{{ $transaction->payment['brand'] or 'n/a' }}</p>
                                <label>Last Four</label>
                                <p>{{ $transaction->payment['last_four'] or 'n/a' }}</p>
                            </div>
                            <div class="col-sm-6">
                                <label>Card Holder</label>
                                <p>{{ $transaction->payment['cardholder'] or 'n/a' }}</p>
                                <label>Zip/Postal</label>
                                <p>{{ $transaction->payment['zip'] or 'n/a' }}</p>
                                <label>Stripe Charge ID</label>
                                <p>{{ $transaction->payment['charge_id'] or 'n/a' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                    @if($transaction->donor)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5 class="panel-header">Donor</h5>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Type</label>
                                        <p>
                                            {{
                                                $transaction->donor->account_id ?
                                                str_singular(ucfirst($transaction->donor->account_type)) .
                                                ' Member' : 'Guest'
                                            }}
                                        </p>
                                        <label>Stripe Customer ID</label>
                                        <p>{{ $transaction->donor->customer_id or 'n/a' }}</p>
                                        <label>Name</label>
                                        <p><a href="{{ url('/admin/donors/'.$transaction->donor->id) }}">{{ $transaction->donor->name }}</a></p>
                                        <label>company</label>
                                        <p>{{ $transaction->donor->company or 'n/a' }}</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Email</label>
                                        <p>{{ $transaction->donor->email }}</p>
                                        <label>Phone</label>
                                        <p>{{ $transaction->donor->phone or 'n/a' }}</p>
                                        <label>Postal/Zip</label>
                                        <p>{{ $transaction->donor->zip or 'n/a' }}</p>
                                        <label>Country</label>
                                        <p>{{ $transaction->donor->country_code ? country($transaction->donor->country_code) : 'n/a' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
            </div>
            <div class="col-sm-6">
                <notes type="transactions"
                       id="{{ $transaction->id }}"
                       user_id="{{ auth()->user()->id }}"
                       :per_page="3"
                       :can-modify="{{ auth()->user()->can('modify-notes') }}">
                </notes>
            </div>
        </div>
    </div>
@stop