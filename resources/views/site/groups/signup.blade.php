@extends('site.layouts.default')

@section('content')
    <div class="dark-bg-primary">
        <div class="container">
            <hr class="divider inv xlg">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2 col-xs-12 col-xs-offset-0 text-center">
                    <a href="{{ $group->slug->url }}"><img class="img-circle img-lg" src="{{ image($group->getAvatar()->source) }}"></a>
                    <h3>{{ $group->name }}</h3>
                </div>
            </div>
            <hr class="divider inv lg">
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                        <h4>I'm Interested in a Trip!</h4>
                    </div>
                    <div class="panel-body">
                        <group-interest-signup id="{{ $group->id }}"></group-interest-signup>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop