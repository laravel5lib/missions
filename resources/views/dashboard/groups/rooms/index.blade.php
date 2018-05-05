@extends('dashboard.layouts.default')

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <hr class="divider inv">
            @breadcrumbs(['links' => [
                'dashboard/groups' => 'Organizations',
                'active' => $group->name
            ]])
            @endbreadcrumbs
            <hr class="divider">
            <hr class="divider inv">
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2">
            @sidenav(['links' => [
                'dashboard/groups/'.$group->id => 'Overview',
                'dashboard/groups/'.$group->id.'/teams' => 'Squads',
                'dashboard/groups/'.$group->id.'/rooms' => 'Rooming'
            ]])
            @endsidenav
        </div>
        <div class="col-sm-10">
            
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-8">
                        <h3 class="hidden-xs"><listen-text event="update-title"></listen-text> <span class="small">&middot; Rooming Plans</span></h3>
                        <h3 class="text-center visible-xs"><listen-text event="update-title"></listen-text> <br><span class="small">Rooming Plans</span></h3>
                    </div>
                    <div class="col-sm-4 text-right">
                        <hr class="divider inv sm">
                        <action-select :normal="false" :options="[]" :api="true" search-route="campaigns" text="Change Campaign" event="campaign-scope" :auto-select-first="true"></action-select>
                        <hr class="divider inv sm visible-xs">
                    </div>
                </div>
            </div>

            <rooming-wizard user-id="{{ Auth::user()->id }}" group-id="{{ $groupId }}"></rooming-wizard>
            <hr class="divider inv xlg">

        </div>
    </div>
</div>
@endsection