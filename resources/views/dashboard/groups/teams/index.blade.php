@extends('dashboard.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-8">
                        <h3 class="hidden-xs"><listen-text event="update-title"></listen-text><small> &middot; Teams</small></h3>
                        <h3 class="text-center visible-xs"><listen-text event="update-title"></listen-text> <br><small>Teams</small></h3>
                    </div>
                    <div class="col-sm-4 text-right">
                       <hr class="divider inv sm">
                       <action-select :normal="false" :options="[]" :api="true" search-route="campaigns" text="Change Campaign" event="campaign-scope" :auto-select-first="true"></action-select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <team-manager user-id="{{ Auth::user()->id }}" group-id="{{ $groupId }}"></team-manager>
        <hr class="divider inv xlg">
    </div>
@endsection

@section('tour')

@endsection