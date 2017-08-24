@extends('site.layouts.default')

@section('content')
    <div class="container">
        <div class="content-section">
            <div class="row">
                <div class="col-sm-5 col-sm-offset-1">
                    @include('errors._403')
                </div><!-- end col -->
            </div><!-- end row -->
        </div><!-- end content-section -->
    </div><!-- end container -->
@endsection