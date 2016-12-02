@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3>{{ $fund->name }} <small>&middot; Fund</small></h3>
                </div>
                <div class="col-sm-4 text-right">
                    <hr class="divider inv sm">
                    <div class="btn-group">
                        <a href="{{ url('admin/funds') }}" class="btn btn-primary-darker"><i class="fa fa-chevron-left"></i></a>
                        <a type="button" class="btn btn-primary" data-toggle="collapse" data-target="#createTransaction"><i class="fa fa-plus icon-left"></i> Transaction</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <fund-manager id="{{ $fund->id }}">
                <notes type="funds"
                       id="{{ $fund->id }}"
                       user_id="{{ auth()->user()->id }}"
                       :per_page="3"
                       :can-modify="{{ auth()->user()->can('modify-notes') }}">
                </notes>
            </fund-manager>
        </div>
    </div>
@stop