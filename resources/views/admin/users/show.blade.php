@extends('admin.layouts.default')

@section('content')

    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3>{{ $user->name }}</h3>
                </div>
                <div class="col-sm-4">
                    <hr class="divider inv sm">
                    <div class="btn-group pull-right">
                        <a href="/admin/users" class="btn btn-primary-darker"><i class="fa fa-chevron-left"></i></a>
                        <div class="btn-group">
                            <a type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action <span class="caret"></span>
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
            </div>

            <div class="col-sm-4">
                <user-permissions user_id="{{ $user->id }}"></user-permissions>
            </div>

        </div>

        <admin-user-delete user-id="{{ $user->id }}"></admin-user-delete>

    </div>
@endsection