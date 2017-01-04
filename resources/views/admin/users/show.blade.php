@extends('admin.layouts.default')

@section('content')

    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3>
                        <img src="{{ image($user->avatar->source . '?w=100') }}" alt="{{ $user->name }}" class="img-circle av-left img-sm">
                        {{ $user->name }}
                        <small>&middot; User</small>
                    </h3>
                </div>
                <div class="col-sm-4 text-right">
                    <hr class="divider inv sm">
                    <hr class="divider inv">
                    <div class="btn-group">
                        <a href="/admin/users" class="btn btn-primary-darker"><i class="fa fa-chevron-left"></i></a>
                        <div class="btn-group">
                            <a type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Manage <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="create">New</a></li>
                                <li><a href="{{ Request::url() }}/edit">Edit</a></li>
                                {{--<li><a data-toggle="modal" data-target="#duplicationModal">Duplicate</a></li>--}}
                                <li role="separator" class="divider"></li>
                                <li><a data-toggle="modal" data-target="#deleteConfirmationModal">Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">

    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <div class="panel panel-default">
                    <div class="panel-body">
                    <div class="col-sm-8">
                        <label>ID</label>
                        <p>{{ $user->id }}</p>

                        <label>Name</label>
                        <p>{{ $user->name }}</p>
                        <hr class="divider">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Email</label>
                                <p>{{ $user->email }}</p>
                            </div>
                            <div class="col-sm-6">
                                <label>Alt. Email</label>
                                <p>{{ $user->alt_email }}</p>
                            </div>
                        </div>
                        <hr class="divider">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Gender</label>
                                <p>{{ $user->gender }}</p>
                            </div>
                            <div class="col-sm-6">
                                <label>Status</label>
                                <p>{{ $user->status }}</p>
                            </div>
                        </div>
                        <hr class="divider">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Birthday</label>
                                <p>{{ carbon($user->birthday)->toFormattedDateString() }}</p>
                            </div>
                            <div class="col-sm-6">
                                <label>Public</label>
                                <p>{{ $user->public ? 'Yes' : 'No'}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 panel panel-default text-center">
                        <div class="panel-body">
                            <label>Phone 1</label>
                            <p>{{ $user->phone_one }}</p>

                            <label>Phone 2</label>
                            <p>{{ $user->phone_two }}</p>

                            <label>Street</label>
                            <p>{{ $user->street }}</p>

                            <label>City</label>
                            <p>{{ $user->city }}</p>

                            <label>State</label>
                            <p>{{ $user->state }}</p>

                            <label>Zip</label>
                            <p>{{ $user->zip }}</p>

                            <label>Country</label>
                            <p>{{ country($user->country_code) }}</p>

                            <label>Timezone</label>
                            <p>{{ $user->timezone }}</p>
                        </div>
                    </div><!-- end col -->
                    </div><!-- end panel body -->
                </div><!-- end panel -->

                <notes type="users"
                       id="{{ $user->id }}"
                       user_id="{{ auth()->user()->id }}"
                       :per_page="3"
                       :can-modify="{{ auth()->user()->can('modify-notes') }}">
                </notes>
            </div>

            <div class="col-sm-4">
                <p class="small text-muted text-center">Temporarily login as this user.</p>
                <a href="{{ url('admin/users/'.$user->id.'/impersonate') }}"
                   class="btn btn-default btn-block"><i class="fa fa-user-secret"></i> Impersonate</a>
                <hr class="divider inv">
                <user-permissions user_id="{{ $user->id }}" user-roles="{{ $user->roles->pluck('name') }}"></user-permissions>
            </div>

        </div>

        <admin-user-delete user-id="{{ $user->id }}"></admin-user-delete>

    </div>
@endsection