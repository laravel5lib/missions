@extends('admin.layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>
                    Users <small>Details</small>
                    <div class="btn-group pull-right">
                        <a href="/admin/users" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>
                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="create">New</a></li>
                            <li><a href="{{ Request::url() }}/edit">Edit</a></li>
                            {{--<li><a data-toggle="modal" data-target="#duplicationModal">Duplicate</a></li>--}}
                            <li role="separator" class="divider"></li>
                            <li><a data-toggle="modal" data-target="#deleteConfirmationModal">Delete</a></li>
                        </ul>
                    </div>
                </h3>
                <hr>
                <dl class="dl-horizontal">
                    <dt>ID</dt>
                    <dd>{{ $user->id }}</dd>

                    <dt>Name</dt>
                    <dd>{{ $user->name }}</dd>

                    <dt>Email</dt>
                    <dd>{{ $user->email }}</dd>

                    <dt>Alt. Email</dt>
                    <dd>{{ $user->alt_email }}</dd>

                    <dt>Gender</dt>
                    <dd>{{ $user->gender }}</dd>

                    <dt>Status</dt>
                    <dd>{{ $user->status }}</dd>

                    <dt>Birthday</dt>
                    <dd>{{ carbon($user->birthday)->toFormattedDateString() }}</dd>

                    <dt>Phone 1</dt>
                    <dd>{{ $user->phone_one }}</dd>

                    <dt>Phone 2</dt>
                    <dd>{{ $user->phone_two }}</dd>

                    <dt>Street</dt>
                    <dd>{{ $user->street }}</dd>

                    <dt>City</dt>
                    <dd>{{ $user->city }}</dd>

                    <dt>State</dt>
                    <dd>{{ $user->state }}</dd>

                    <dt>Zip</dt>
                    <dd>{{ $user->zip }}</dd>

                    <dt>Country</dt>
                    <dd>{{ country($user->country_code) }}</dd>

                    <dt>Timezone</dt>
                    <dd>{{ $user->timezone }}</dd>

                    <dt>Public</dt>
                    <dd>{{ $user->public ? 'Yes' : 'No'}}</dd>

                    <dt>Admin</dt>
                    <dd>{{ $user->admin ? 'Yes' : 'No' }}</dd>
                </dl>
            </div>
        </div>

        <admin-user-delete user-id="{{ $user->id }}"></admin-user-delete>

    </div>
@endsection