@extends('layouts.admin')

@section('content')

@breadcrumbs(['links' => [
    'admin' => 'Dashboard',
    'admin/donors' => 'Donors',
    'active' => $donor->name
]])
@endbreadcrumbs

    <hr class="divider inv lg">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-7 col-md-offset-2">
                @component('panel')
                    @slot('title')
                        <div class="row">
                            <div class="col-xs-8">
                                <h5>Donor</h5> 
                            </div>
                            <div class="col-xs-4 text-right">
                            @can('update', $donor)
                                <a href="{{ url('admin/donors/'. $donor->id .'/edit') }}" class="btn btn-primary btn-sm">
                                    Edit
                                </a>
                            @endcan
                            </div>
                        </div>
                    @endslot
                    @component('list-group', ['data' => [
                        'Name' => $donor->name,
                        'type' => ($donor->account_id ?
                                    str_singular(ucfirst($donor->account_type)) .
                                    ' Member' : 'Guest'),
                        'Stripe Customer ID' => '<code>'.$donor->customer_id.'</code>',
                        'Email' => $donor->email,
                        'Phone' => $donor->phone,
                        'Postal/Zip' => $donor->zip,
                        'Country' => ($donor->country_code ? country($donor->country_code) : 'n/a'),
                        'Company' => $donor->company
                    ]])
                    @endcomponent
                @endcomponent

                @can('view', \App\Models\v1\Transaction::class)
                    @component('panel')
                        @slot('title')<h5>Transactions</h5> @endslot
                        @slot('body')
                            <admin-transactions-list donor="{{ $donor->id }}"
                                                    storage-name="AdminDonorTransactionsConfig">
                            </admin-transactions-list>
                        @endslot
                    @endcomponent
                @endcan
            </div>
            <div class="col-xs-12 col-md-3">
                @can('view', \App\Models\v1\Note::class)
                        <notes type="donors"
                            id="{{ $donor->id }}"
                            user_id="{{ auth()->user()->id }}"
                            :per_page="10">
                        </notes>
                @endcan
            </div>
        </div>
    </div>
@stop