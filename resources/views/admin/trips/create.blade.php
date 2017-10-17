@extends('admin.layouts.default')

@section('content')
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <campaign-trip-form :campaign="{{ $campaign }}"></campaign-trip-form>
            </div><!-- end col -->
        </div><!-- end row -->
    </div><!-- end container -->
@endsection