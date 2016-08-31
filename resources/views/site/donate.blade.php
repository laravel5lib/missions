@extends('site.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>Donate</h3>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <donate stripe-key="{{ env('STRIPE_PUBLIC_KEY') }}" auth="{{ auth()->check() ? 1 : 0 }}" type="{{ $type or '' }}" type-id="{{ $slug or '' }}"></donate>
                    </div>
                </div>
            </div>
        </div>
    </div>
<hr class="divider inv xlg">
@endsection