@extends('site.layouts.default')

@section('content')
    <div class="login-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-body text-center">

                            <h3>Let's Go!</h3>

                            <p class="lead">You've successfully verified your email address.</p>

                            <p>
                            <a href="{{ route('verify.email.continue', ['userId' => $userId]) }}" class="btn btn-primary">
                                Continue
                            </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection