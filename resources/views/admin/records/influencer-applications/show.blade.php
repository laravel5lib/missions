@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3 class="hidden-xs">User Influencer Application <small>&middot; {{ $essay->author_name }}</small></h3>
                    <h3 class="visible-xs text-center">User Influencer Application<br><small>{{ $essay->author_name }}</small></h3>
                </div>
                <div class="col-sm-4 text-right hidden-xs">
                    <hr class="divider inv sm">
                    <div class="btn-group">
                        <a onclick="window.history.back()" class="btn btn-primary-darker">
                            <span class="fa fa-chevron-left icon-left"></span>
                        </a>
                        <a href="{{ url('admin/records/influencers/' . $essay->id . '/edit') }}" class="btn btn-primary">
                            Edit
                        </a>
                    </div>
                </div>
                <div class="col-sm-4 text-center visible-xs">
                    <div class="btn-group">
                        <a onclick="window.history.back()" class="btn btn-primary-darker">
                            <span class="fa fa-chevron-left icon-left"></span> Back
                        </a>
                        {{-- <a href="{{ url('admin/records/influencers/' . $essay->id . '/edit') }}" class="btn btn-primary">
                            Edit
                        </a> --}}
                    </div>
                    <hr class="divider inv sm">
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv">
    @include('_essay', $essay)

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <notes type="essays" id="{{ $essay->id }}" :can-modify="1"></notes>
            </div>
        </div>
    </div>
@endsection