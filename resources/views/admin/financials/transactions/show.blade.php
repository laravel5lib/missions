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
                    <div class="btn-group">
                        <a href="{{ url('admin/transactions') }}" class="btn btn-primary-darker"><i class="fa fa-chevron-left"></i></a>
                        <button type="button"
                                class="btn btn-primary"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                            Manage <i class="fa fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu">
                            @if($transaction->type == 'donation')
                                <li><a data-toggle="modal" data-target="#refund">Refund Transaction</a></li>
                            @endif
                            <li><a data-toggle="modal" data-target="#deleteConfirmationModal">Delete</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-offset-2 col-sm-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-header">Details</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-6">
                                <label>Amount</label>
                                @if($transaction->amount > 0)
                                    <h4 class="text-success">$ {{ $transaction->amount }}</h4>
                                @else
                                    <h4 class="text-danger">$ {{ $transaction->amount }}</h4>
                                @endif
                                <label>Type</label>
                                <p>{{ str_singular(ucwords($transaction->type)) }}</p>
                                <label>QuickBooks Class</label>
                                <p>{{ $transaction->fund->class }}</p>
                                <label>Created</label>
                                <p>{{ $transaction->created_at->format('M j, Y h:i a') }}</p>
                            </div>
                            <div class="col-xs-6">
                                <label>Description</label>
                                <p>{{ $transaction->description }}</p>
                                <label>Designation</label>
                                <p><a href="{{ url('/admin/funds/'. $transaction->fund->id) }}">{{ $transaction->fund->name }}</a></p>
                                <label>QuickBooks Item</label>
                                <p>{{ $transaction->fund->item }}</p>
                                <label>Last Updated</label>
                                <p>{{ $transaction->updated_at->format('M j, Y h:i a') }}</p>
                            </div>
                        </div>

                        @if($transaction->details && $transaction->type == 'credit')
                        <div class="row">
                            <div class="col-xs-12">
                                <label>Reason</label>
                                <p>{{ $transaction->details['reason'] }}</p>
                            </div>
                        </div>
                        @endif

                        @if($transaction->details && $transaction->type == 'refund')
                        <div class="row">
                            <div class="col-xs-6">
                                <label>Related Transaction</label>
                                <p><a href="/admin/transactions/{{ $transaction->details['transaction_id'] }}">
                                    {{ $refund->findOrFail($transaction->details['transaction_id'])->description }}
                                </a></p>
                            </div>
                            <div class="col-xs-6">
                                <label>Reason</label>
                                <p>{{ ucfirst($transaction->details['reason']) }}</p>
                                <label>Stripe Refund ID</label>
                                <p>{{ $transaction->details['refund_id'] or 'n/a' }}</p>
                            </div>
                        </div>
                        @endif

                        @if($transaction->details && $transaction->type == 'transfer')
                            <div class="row">
                                <div class="col-xs-12">
                                    <label>Related Transaction</label>
                                    <p><a href="/admin/transactions/{{ $transaction->details['related_transaction_id'] }}">
                                        {{ $transaction->details['related_transaction_id'] }}
                                    </a></p>
                                </div>
                            </div>
                        @endif

                        @if($transaction->details && $transaction->type == 'donation')
                            <div class="row">
                                <div class="col-xs-6">
                                    <label>Given Anonymously</label>
                                    <p>{{ $transaction->anonymous ? 'Yes' : 'No' }}</p>
                                </div>
                                <div class="col-xs-6">
                                    <label>Payment Type</label>
                                    <p>{{ $transaction->details['type'] or 'n/a' }}</p>
                                </div>
                            </div>

                            @if($transaction->details['type'] == 'card')
                                <div class="row">
                                    <div class="col-xs-6">
                                        <label>Card Brand</label>
                                        <p>{{ $transaction->details['brand'] or 'n/a' }}</p>
                                        <label>Last Four</label>
                                        <p>{{ $transaction->details['last_four'] or 'n/a' }}</p>
                                    </div>
                                    <div class="col-xs-6">
                                        <label>Card Holder</label>
                                        <p>{{ $transaction->details['cardholder'] or 'n/a' }}</p>
                                        <label>Stripe Charge ID</label>
                                        <p>{{ $transaction->details['charge_id'] or 'n/a' }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($transaction->details['type'] == 'check')
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label>Check Number</label>
                                        <p>{{ $transaction->details['number'] or 'n/a' }}</p>
                                    </div>
                                </div>
                            @endif

                        @endif

                    </div>
                </div>
            </div>
        </div>

        @if($transaction->donor)
        <div class="row">
            <div class="col-sm-offset-2 col-sm-8">
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
                    <div class="panel-footer text-center">
                        <hr class="divider inv sm">
                        <send-email label="Resend Receipt Email" 
                                 icon="fa fa-envelope icon-left"
                                 class="btn btn-default btn-sm"
                                 command="email:send-receipt" 
                                 :parameters="{id: '{{ $transaction->id }}', email: '{{ $transaction->donor->email }}'}">
                        </send-email>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-sm-offset-2 col-sm-8">
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

    <transaction-delete transaction-id="{{ $transaction->id }}"></transaction-delete>
@stop