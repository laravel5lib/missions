@extends('site.layouts.default')

@section('content')
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @include('site.fundraisers.nav')
            </div>
            <div class="col-md-9">
                <h3>Fundrasier Updates</h3>

                <fundraisers-stories id="{{ $fundraiser->uuid }}"
                    sponsor-id="{{ $fundraiser->sponsor_id }}"
                    auth-id="{{ (auth()->check() ? auth()->user()->id : '') }}">
                </fundraisers-stories>

            </div><!-- end col -->
        </div><!-- end row -->
        <hr class="divider inv xlg">
    </div><!-- end container -->
    <markdown-example-modal></markdown-example-modal>
@stop