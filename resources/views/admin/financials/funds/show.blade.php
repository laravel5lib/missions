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
    @if($fund->deleted_at)
    <div class="darker-bg-primary">
        <div class="container">
            <div class="col-sm-8 text-center">
                <hr class="divider inv sm">
                <h5>This fund was archived {{ $fund->deleted_at->toDayDateTimeString() }} UTC and is no longer active.</h5>
                <hr class="divider inv sm">
            </div>
            <div class="col-sm-4 text-center">
                <hr class="divider inv sm hidden-xs">
                <button data-toggle="modal" data-target="#restoreConfirmationModal" class="btn btn-sm btn-white-hollow"><i class="fa fa-undo"></i> Restore</button>
                <hr class="divider inv sm">
            </div>
        </div><!-- end container -->
    </div><!-- end dark-bg-primary -->
    @endif
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <fund-manager id="{{ $fund->id }}">
                <notes type="funds"
                       id="{{ $fund->id }}"
                       user_id="{{ auth()->user()->id }}"
                       :per_page="3"
                       :can-modify="{{ auth()->user()->can('modify-notes')?1:0 }}">
                </notes>
            </fund-manager>
            <restore-fund id="{{ $fund->id }}"></restore-fund>
        </div>
    </div>
@stop