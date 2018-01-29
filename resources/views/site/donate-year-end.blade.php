@extends('site.layouts.default')

@section('content')
<div class="donate-ye-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-push-6 col-md-5">
                <img class="donate-ye-logo img-responsive" src="../images/donate/year-end-logo.png" />
                <hr class="divider inv">
                <h4 style="line-height:28px" class="text-center">Give a year end donation and be part of building A New Missions Era.</h4>
            </div><!-- end col -->
            <div class="col-sm-6 col-sm-pull-6 col-sm-offset-0 col-md-4 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <donate auth="{{ auth()->check() ? 1 : 0 }}" type="{{ $type or '' }}" type-id="{{ $slug or '' }}" recipient="{{$recipient or 'Missions.Me'}}" fund-id="{{ $id }}" stripe-key="{{ env('STRIPE_PUBLIC_KEY') }}"></donate>
                    </div>
                    <div class="panel-footer text-center">
                        <a href="https://stripe.com/" target="_blank"><span style="font-size:.6em;color:#bcbcbc;text-transform:uppercase;letter-spacing:1px;">Securely</span> <img style="width:90px; height:20px;opacity:.65;" src="../images/powered-by-stripe@2x.png" alt="Powered by Stripe"></a>
                    </div>
                </div>
                <div class="text-center">
                    <h6 class="text-uppercase"><a style="color:#296e75;" href="{{ url('support-monthly') }}"><i class="fa fa-refresh icon-left"></i> Give A Recurring Donation</a></h6>
                </div>
            </div><!-- end col -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
@endsection
