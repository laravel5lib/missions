@extends('admin.layouts.default')
@inject('refund', 'App\Models\v1\Transaction')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3>{{ ucwords($transaction->type) }} Details</h3>
                </div>
                <div class="col-sm-4 text-right">
                    <hr class="divider inv sm">
                    <!-- Split button -->
                    <div class="btn-group">
                        <a type="button" href="{{ url('admin/transactions/' . $transaction->id . '/edit') }}" class="btn btn-primary">Edit</a>
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-angle-down"></i>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            @if($transaction->type == 'donation')
                                <li><a data-toggle="modal" data-target="#refund">Refund Transaction</a></li>
                            @endif
                            <li><a href="#">Delete Transaction</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ url('admin/transactions') }}">Back to all Transactions</a></li>
                        </ul>
                    </div>
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
                        <p>{{ $transaction->created_at->format('M j, Y h:i a') }}</p>
                        <label>Last Updated</label>
                        <p>{{ $transaction->updated_at->format('M j, Y h:i a') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                @if($transaction->payment && $transaction->type == 'refund')
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5 class="panel-header">Related Transaction</h5>
                        </div>
                        <div class="panel-body">
                            <label>Transaction</label>
                            <p>
                                <a href="/admin/transactions/{{ $transaction->payment['transaction_id'] }}">
                                    {{ $refund->findOrFail($transaction->payment['transaction_id'])->description }}
                                </a>
                            </p>
                            <label>Reason</label>
                            <p>{{ ucfirst($transaction->payment['reason']) }}</p>
                            <label>Stripe Refund ID</label>
                            <p>{{ $transaction->payment['refund_id'] or 'n/a' }}</p>
                        </div>
                    </div>
                @endif

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

    @if($transaction->type == 'donation')
    <div class="modal fade" id="refund" tabindex="-1">
        <div class="modal-dialog">
            <refund-form transaction-id="{{ $transaction->id }}"></refund-form>
        </div>
    </div>
    @endif
@stop