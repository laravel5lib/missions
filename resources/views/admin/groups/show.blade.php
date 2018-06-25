@extends('layouts.admin')

@section('content')

    @breadcrumbs([ 'links' => [
        'admin' => 'Dashboard', 'admin/organizations' => 'Organizations', 
        'active' => $group->name
    ]])
    @endbreadcrumbs
    
<hr class="divider inv lg">

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-md-7 col-md-offset-2">
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="details">
                    <div class="row">
                        <div class="col-xs-12">
                            @component('panel')
                                @slot('title')
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <h5>Group Details</h5>
                                        </div>
                                        <div class="col-xs-4 text-right">
                                            @can('update', $group)
                                            <a href="{{ url('/admin/organizations/'.$group->id.'/edit')}}" class="btn btn-sm btn-primary">Edit</a>
                                            @endcan
                                        </div>
                                    </div>
                                @endslot
                                @component('list-group', ['data' => [
                                    'Name' => $group->name,
                                    'Type' => $group->type,
                                    'Status' => ($group->public ? 'Public' : 'Private'),
                                    'Email' => $group->email,
                                    'Primary Phone' => $group->phone_one,
                                    'Secondary Phone' => $group->phone_two,
                                    'Timezone' => $group->timezone,
                                    'Address' => $group->address_one.'<br />'.$group->address_two.'<br />'.$group->city.', '.$group->state.' '.$group->zip.'<br />'.country($group->country_code),
                                    'Description' => $group->description,
                                    'Created' => '<datetime-formatted value="'.$group->created_at->toIso8601String().'" />',
                                    'Last Updated' => '<datetime-formatted value="'.$group->updated_at->toIso8601String().'" />'
                                ]])
                                @endcomponent
                            @endcomponent
                        </div>
                    </div>

                    @unless( ! $group->slug)
                        <div class="row">
                            <div class="col-xs-12">
                                @component('panel')
                                    @slot('title')
                                        <h5>Public Profile</h5>
                                    @endslot
                                    @slot('body')
                                        @if($group->public)
                                            <label>Links</label>
                                            <pre><a href="/{{ $group->slug->url }}">http://missions.me/{{ $group->slug->url }}</a></pre>
                                        @else
                                            <pre class="text-strike">http://missions.me/{{ $group->slug->url }}</pre>
                                        @endif
                                        <label>Trip Interest Form</label>
                                        <pre><a href="/{{ $group->slug->url }}/signup">http://missions.me/{{ $group->slug->url }}/signup</a></pre>
                                    @endslot
                                @endcomponent
                            </div>
                        </div>
                    @endunless

                    <div class="row">
                        <div class="col-xs-12">
                            <admin-group-managers group-id="{{ $group->id }}"></admin-group-managers>
                        </div>
                    </div>
                    
                    @can('delete', $group)
                    <div class="row">
                        <div class="col-xs-12">
                            @component('panel')
                                @slot('title')
                                    <h5>Delete Organization</h5>
                                @endslot
                                @slot('body')
                                    <div class="alert alert-warning">
                                        <div class="row">
                                            <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                                            <div class="col-xs-11">USE CAUTION! This is a destructive action that cannot be undone. This will permanently delete the organization, all of it's trips and reservations.</div>
                                        </div>
                                    </div>
                                    <delete-form url="groups/{{ $group->id }}" 
                                        redirect="/admin/organizations"
                                        label="Enter the organization name to delete it"
                                        match-value="{{ $group->name }}"
                                    ></delete-form>
                                @endslot
                            @endcomponent
                        </div>
                    </div>
                    @endcan

                </div>
                <div role="tabpanel" class="tab-pane" id="trips">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h5>Group Trips</h5>
                                </div>
                                <div class="panel-body">
                                    <admin-group-trips group-id="{{ $group->id }}"></admin-group-trips>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 small">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#notes" aria-controls="notes" role="tab" data-toggle="tab">Notes</a>
                </li>
                <li role="presentation">
                    <a href="#tasks" aria-controls="tasks" role="tab" data-toggle="tab">Tasks</a>
                </li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="notes">
                    <notes type="groups"
                        id="{{ $group->id }}"
                        user_id="{{ auth()->user()->id }}"
                        :per_page="10"
                        :can-modify="{{ auth()->user()->can('modify-notes')?1:0 }}">
                    </notes>
                </div>
                <div role="tabpanel" class="tab-pane" id="tasks">
                    <todos type="groups"
                        id="{{ $group->id }}"
                        user_id="{{ auth()->user()->id }}"
                        :can-modify="{{ auth()->user()->can('modify-todos')?1:0 }}">
                    </todos>
                </div>
            </div>

        </div>
    </div>
</div>
<admin-group-delete group-id="{{ $group->id }}"></admin-group-delete>
@endsection