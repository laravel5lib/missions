@extends('site.layouts.default')

@section('content')
<div class="donate-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-push-6 col-md-6 home-half-section">
                <h1 class="text-hero">Become A Difference Maker.</h1>
                <p>When you give, you are tangibly making a<br>difference in someone's life.</p>
            </div><!-- end col -->
            <div class="col-sm-6 col-sm-pull-6 col-sm-offset-0 col-md-4 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <donate auth="{{ auth()->check() ? 1 : 0 }}" type="{{ $type or '' }}" type-id="{{ $slug or '' }}" recipient="{{$recipient or 'Missions.Me'}}" fund-id="{{ $id }}" stripe-key="{{ env('STRIPE_PUBLIC_KEY') }}"></donate>
                    </div>
                    <div class="panel-footer text-center">
                        <a href="https://stripe.com/" target="_blank"><span style="font-size:.6em;color:#bcbcbc;text-transform:uppercase;letter-spacing:1px;">Securely</span> <img style="width:90px; height:20px;opacity:.65;" src="../images/powered-by-stripe@2x.png" alt="Powered by Stripe"></a>
                    </div>
                </div>
            </div><!-- end col -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
@endsection
