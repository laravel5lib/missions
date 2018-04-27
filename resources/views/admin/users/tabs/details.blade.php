@extends('admin.users.show')

@section('tab')

@component('panel')
    @slot('title')
        <div class="row">
            <div class="col-xs-8">
                <h5>User Details</h5>
            </div>
            <div class="col-xs-4 text-right">
                <div class="btn-group btn-group-sm">
                    <a type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Manage <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        @can('update', $user)
                            <li><a href="{{ Request::url() }}/edit">Edit</a></li>
                        @endcan
                        @can('delete', $user)
                            <li role="separator" class="divider"></li>
                            <li><a data-toggle="modal" data-target="#deleteConfirmationModal">Delete</a></li>
                        @endcan
                    </ul>
                </div>
            </div>
        </div>
    @endslot
    @component('list-group', ['data' => [
        'First Name' => $user->first_name,
        'Last Name' => $user->last_name,
        'Gender' => $user->gender,
        'Marital Status' => $user->status,
        'Birthday' => $user->birthday->toFormattedDateString(),
        'Email' => $user->email,
        'Alternate Email' => $user->alt_email,
        'Primary Phone' => $user->phone_one,
        'Secondary Phone' => $user->phone_two,
        'Address' => $user->address.'<br />'.$user->city.', '.$user->state.' '.$user->zip,
        'Country' => country($user->country_code),
        'Timezone' => $user->timezone,
        'Profile' => ($user->public ? 'Public' : 'Private')
    ]])
    @endcomponent
    @slot('footer')
    <div class="text-right">
        <send-email label="Resend Welcome Email"
                        icon="fa fa-envelope icon-left"
                        classes="btn btn-default btn-sm"
                        command="email:send-welcome"
                        :parameters="{id: '{{ $user->id }}', email: '{{ $user->email }}'}">
        </send-email>
    </div>
    @endslot
@endcomponent

@endsection