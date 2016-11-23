@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h3>Edit {{ ucwords($transaction->type) }}</h3>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-offset-2 col-sm-8">
                <transaction-form fund-id="{{ $transaction->fund->id }}"
                                  id="{{ $transaction->id }}"
                                  :editing="true">
                </transaction-form>
            </div>
        </div>
    </div>
@endsection