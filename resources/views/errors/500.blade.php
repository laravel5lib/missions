@extends('master')

@section('layout')
    <div class="container">
        <div class="content-section">
            <div class="row">
                <div class="col-sm-5 col-sm-offset-1">
                    <h1 class="text-hero">Yikes!</h1>
                    <h3>There seems to be an internal server error.</h3>
                    <p>Error code 500</p>
                    <p class="small">Please contact the server administrator and inform them of the time the error occured. If possible, please explain the steps you took leading up to the error. We apologize for any inconvience.</p>
                </div><!-- end col -->
            </div><!-- end row -->
        </div><!-- end content-section -->
    </div><!-- end container -->
@endsection