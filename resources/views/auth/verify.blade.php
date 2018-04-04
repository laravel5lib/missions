@extends('site.layouts.default')

@section('content')
    <div class="container">
        <div class="content-section">
            <div class="row">
                <div class="col-md-6 col-md-offset-1">
                    <h1 class="text-hero">Verify your Email</h1>
                    <h3>
                        We just sent an email to<br>
                        <small>{{ $user->email }}</small>
                    </h3>
                    <p>Please check your inbox for a confirmation email. Click the link in the email to confirm your email address.</p>
                    <hr class="divider inv">
                    <h5>Don't see it in your inbox?</h5>
                    <form method="POST" action="{{ route('verify.email.resend') }}">
                        {{ csrf_field() }}
                        <input name="user_id" type="hidden" value="{{ $user->id }}">
                        <button class="btn btn-sm btn-primary">Resend Email</button>
                    </form>
                    <hr class="divider inv">
                    <p class="small">Make sure the email address listed above is correct and check your spam or junk mail folder.</p>
                </div><!-- end col -->
            </div><!-- end row -->
        </div><!-- end content-section -->
    </div><!-- end container -->
@endsection