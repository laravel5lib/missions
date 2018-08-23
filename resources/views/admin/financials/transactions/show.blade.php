@extends('layouts.admin')
@inject('refund', 'App\Models\v1\Transaction')

@section('content')
    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/transactions' => 'Transactions',
        'active' => 'Details'
    ]])
    @endbreadcrumbs
<hr class="divider inv lg">
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-md-7 col-md-offset-2">
            @component('panel')
                @slot('body')
                    <div class="row">
                        <div class="col-sm-3">
                            <h4 class="text-primary">{{ '$'.$transaction->amountInDollars() }}</h4>
                            <p class="small text-muted">Amount</p>
                        </div>
                        <div class="col-sm-4">
                            <h4>{{ str_singular(ucwords($transaction->type)) }}</h4>
                            <p class="small text-muted">Type</p>
                        </div>
                        <div class="col-sm-5">
                            <h4>{{ $transaction->created_at->format('M j, Y h:i a') }}</h4>
                            <p class="small text-muted">Date</p>
                        </div>
                    </div>
                @endslot
            @endcomponent

            @component('panel')
                @slot('title')
                    <div class="row">
                        <div class="col-xs-6">
                            <h5>Details</h5>
                        </div>
                        <div class="col-xs-6 text-right">
                        @can('update', \App\Models\v1\Transaction::class)
                            <div class="btn-group btn-group-sm">
                                <button type="button"
                                        class="btn btn-primary btn-sm"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false">
                                    Manage <i class="fa fa-angle-down"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    @if($transaction->type == 'donation')
                                        <li><a data-toggle="modal" data-target="#refund">Refund Transaction</a></li>
                                    @endif
                                    @can('delete', \App\Models\v1\Transaction::class)
                                        <li>
                                            <a data-toggle="modal" data-target="#deleteConfirmationModal">Delete</a>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        @endcan
                        </div>
                    </div>
                @endslot
                @component('list-group', ['data' => [
                    'Amount' => '$'.$transaction->amountInDollars(),
                    'Type' => str_singular(ucwords($transaction->type)),
                    'Date' => $transaction->created_at->format('M j, Y h:i a'),
                    'Description' => $transaction->description,
                    'Designation' => '<a href="'.url('/admin/funds/'. $transaction->fund->id).'">'.$transaction->fund->name.'</a>'
                ]])
                @endcomponent
            @endcomponent

            @if($transaction->details && $transaction->type == 'credit')
                @component('panel')
                    @slot('title')<h5>Credit Details</h5> @endslot
                    @component('list-group', ['data' => [
                        'Reason' => $transaction->details['reason']
                    ]])
                    @endcomponent
                @endcomponent
            @endif

            @if($transaction->details && $transaction->type == 'refund')
                @component('panel')
                    @slot('title')<h5>Refund Details</h5> @endslot
                    @component('list-group', ['data' => [
                        'Stripe Refund ID' => '<code>'.$transaction->details['refund_id'].'</code>',
                        'Reason' => ucfirst($transaction->details['reason']),
                        'Related Transaction' => '<a href="/admin/transactions/'.$transaction->details['transaction_id'].'">'.
                        $refund->findOrFail($transaction->details['transaction_id'])->description.'</a>',
                    ]])
                    @endcomponent
                @endcomponent
            @endif

            @if($transaction->details && $transaction->type == 'transfer')
                @component('panel')
                    @slot('title')<h5>Transfer Details</h5> @endslot
                    @component('list-group', ['data' => [
                        'Related Transaction' => '<a href="/admin/transactions/'.$transaction->details['related_transaction_id'].'">'.$transaction->details['related_transaction_id'].'</a>'
                    ]])
                    @endcomponent
                @endcomponent
            @endif
            
            @if($transaction->details && $transaction->type == 'donation')
                @component('panel')
                    @slot('title')
                        <h5>Payment Method</h5>
                    @endslot
                    @if($transaction->details['type'] == 'card')
                        @component('list-group', ['data' => [
                            'Given Anonymously' => ($transaction->anonymous ? 'Yes' : 'No'),
                            'Payment Type' =>  $transaction->details['type'],
                            'Card Holder' => $transaction->details['cardholder'],
                            'Card Brand' => $transaction->details['brand'],
                            'Last Four' => $transaction->details['last_four'],
                            'Stripe Charge ID' => '<code>'.$transaction->details['charge_id'].'</code>'
                        ]])
                        @endcomponent
                    @endif
                    @if($transaction->details['type'] == 'check')
                        @component('list-group', ['data' => [
                            'Given Anonymously' => ($transaction->anonymous ? 'Yes' : 'No'),
                            'Payment Type' =>  $transaction->details['type'],
                            'Check Number' => $transaction->details['number']
                        ]])
                        @endcomponent
                    @endif
                    @slot('footer')
                        <div class="text-right">
                            <send-email label="Email Receipt"
                                icon="fa fa-envelope icon-left"
                                classes="btn btn-default btn-sm"
                                command="email:send-receipt"
                                :parameters="{id: '{{ $transaction->id }}', email: '{{ $transaction->donor->email }}'}">
                            </send-email>
                        </div>
                    @endslot
                @endcomponent

            @endif
            
            @if($transaction->donor)
                @component('panel')
                    @slot('title')
                        <h5>Donor</h5>
                    @endslot
                    @component('list-group', ['data' => [
                        'Name' => '<a href="'.url('/admin/donors/'.$transaction->donor->id).'">'.$transaction->donor->name.'</a>',
                        'type' => ($transaction->donor->account_id ?
                                    str_singular(ucfirst($transaction->donor->account_type)) .
                                    ' Member' : 'Guest'),
                        'Stripe Customer ID' => '<code>'.$transaction->donor->customer_id.'</code>',
                        'Email' => $transaction->donor->email,
                        'Phone' => $transaction->donor->phone,
                        'Postal/Zip' => $transaction->donor->zip,
                        'Country' => ($transaction->donor->country_code ? country($transaction->donor->country_code) : 'n/a'),
                        'Company' => $transaction->donor->company
                    ]])
                    @endcomponent
                @endcomponent
            @endif
        </div>
        <div class="col-xs-12 col-md-3 small">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#notes" aria-controls="notes" role="tab" data-toggle="tab">Notes</a>
                </li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="notes">
                    <notes type="transactions"
                        id="{{ $transaction->id }}"
                        user_id="{{ auth()->user()->id }}"
                        :per_page="10">
                    </notes>
                </div>
            </div>
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