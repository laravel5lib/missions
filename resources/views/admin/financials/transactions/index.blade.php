@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3>All Transactions</h3>
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
            <div class="col-sm-12">
                @include('admin.financials.partials._tabs')
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th><i class="fa fa-cog"></i></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td><span class="label label-default">{{ ucwords($transaction->type) }}</span></td>
                            <td>{{ $transaction->description }}</td>
                            <td>
                                @if($transaction->amount > 0)
                                    <span class="text-success">$ {{ $transaction->amount }}</span>
                                @else
                                    <span class="text-danger">$ {{ $transaction->amount }}</span>
                                @endif
                            </td>
                            <td><a href="{{ url('/admin/transactions/' . $transaction->id) }}"><i class="fa fa-cog"></i></a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                {{ $transactions->links() }}
            </div>
        </div>
    </div>
@stop