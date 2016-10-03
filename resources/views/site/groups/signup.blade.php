@extends('site.layouts.default')

@section('content')
    <div class="dark-bg-primary">
        <div class="container">
            <hr class="divider inv xlg">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2 col-xs-12 col-xs-offset-0 text-center">
                    <a href="/groups/{{ $group->url }}"><img class="img-circle img-lg" src="{{ image($group->avatar->source) }}"></a>
                    <h3>{{ $group->name }}</h3>
                </div>
            </div>
            <hr class="divider inv lg">
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <label>Trip Interest</label>
                            </div>
                        </div>
                        <hr class="divider inv sm">
                        <group-interest-signup id="{{ $group->id }}"></group-interest-signup>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop