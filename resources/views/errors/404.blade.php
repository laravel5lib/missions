@extends('site.layouts.default')

@section('content')
    <div class="container">
        <div class="content-section">
            <div class="row">
                <div class="col-sm-5 col-sm-offset-1">
                    <h1 class="text-hero">Oops!</h1>
                    <h3>We can't seem to find the page you're looking for.</h3>
                    <p>Error code 404</p>
                    <p class="small">It may have been moved or deleted. Please check your spelling, you may have stumbled upon this error by accident.</p>
                    <hr class="divider inv">
                    <h6>Here are some helpful links:</h6>
                    <ul class="list-unstyled">
                        <li><a href="https://missions.me">Home</a></li>
                        <li><a href="/contact">Contact Us</a></li>
                        <li><a href="/campaigns">Find A Trip</a></li>
                        <li><a href="/fundraisers">Donate To A Cause</a></li>
                        <li><a href="/groups">Groups</a></li>
                    </ul>
                </div><!-- end col -->
            </div><!-- end row -->
        </div><!-- end content-section -->
    </div><!-- end container -->
@endsection